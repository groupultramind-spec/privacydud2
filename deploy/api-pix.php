<?php
/**
 * API PIX - Gerar QR Code usando Ecompag
 */

header('Content-Type: application/json');
require_once 'config.php';

// Receber dados do POST
$data = json_decode(file_get_contents('php://input'), true);

// Validar dados recebidos
if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos'
    ]);
    exit;
}

// Extrair e validar campos
$nome = sanitize($data['name'] ?? '');
$email = sanitize($data['email'] ?? '');
$cpf = preg_replace('/[^0-9]/', '', $data['cpf'] ?? '');
$phone = sanitize($data['phone'] ?? '');
$plano = sanitize($data['plan'] ?? 'monthly');

// Obter preços do site_data.json para evitar manipulação de valor
$siteDataFile = __DIR__ . '/site_data.json';
$siteData = file_exists($siteDataFile) ? json_decode(file_get_contents($siteDataFile), true) : [];
$precos = $siteData['precos'] ?? [];

$defaultPrices = [
    'mensal' => 32.99,
    'semanal' => 19.90,
    'trimestral' => 121.00,
    'anual' => 299.00,
    'vitalicio' => 525.50,
    'pacote_video' => 14.90,
    'desconto' => 9.90
];

$planoKey = $plano;
if ($plano === 'monthly') $planoKey = 'mensal';
elseif ($plano === 'quarterly') $planoKey = 'trimestral';
elseif ($plano === 'yearly') $planoKey = 'anual';

if (array_key_exists($planoKey, $defaultPrices)) {
    $valor = isset($precos[$planoKey]) ? floatval($precos[$planoKey]) : $defaultPrices[$planoKey];
} else {
    $valor = floatval($data['amount'] ?? 0);
}

// Validações
$errors = [];

if (empty($nome) || strlen($nome) < 3) {
    $errors[] = 'Nome inválido';
}

if (!validarEmail($email)) {
    $errors[] = 'Email inválido';
}

if (!validarCPF($cpf)) {
    $errors[] = 'CPF inválido';
}

if ($valor <= 0) {
    $errors[] = 'Valor inválido';
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de validação',
        'errors' => $errors
    ]);
    exit;
}

// Descrição do pagamento baseado no plano
$descricoes = [
    'monthly' => 'Assinatura Mensal - Privacy Eduarda',
    'quarterly' => 'Assinatura Trimestral - Privacy Eduarda',
    'yearly' => 'Assinatura Anual - Privacy Eduarda'
];
$descricao = $descricoes[$plano] ?? 'Assinatura Privacy - Eduarda';

// Preparar dados para API BuyPix
$postData = [
    'amount' => $valor,
    'webhook_url' => WEBHOOK_URL,
    'payer_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
    'use_delay' => true
];

function generateUUIDv4() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
$idempotencyKey = generateUUIDv4();

// Fazer requisição para BuyPix
$ch = curl_init('https://buypix.me/api/v1/deposits');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . BUYPIX_API_KEY,
        'Content-Type: application/json',
        'X-Idempotency-Key: ' . $idempotencyKey,
    ],
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Log da resposta para debug
file_put_contents('api-pix.log', 
    date('Y-m-d H:i:s') . " - Request: " . json_encode($postData) . "\nResponse: " . $response . PHP_EOL, 
    FILE_APPEND
);

if ($curlError) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao conectar com o gateway de pagamento'
    ]);
    exit;
}

$apiResponse = json_decode($response, true);

if (($httpCode === 200 || $httpCode === 201) && isset($apiResponse['success']) && $apiResponse['success'] && isset($apiResponse['data']['pix_qr_code'])) {
    // Salvar pagamento como pendente
    $paymentId = generateUniqueId();
    $transactionId = $apiResponse['data']['id'];
    
    $paymentData = [
        'payment_id' => $paymentId,
        'transaction_id' => $transactionId,
        'status' => 'pending',
        'nome' => $nome,
        'email' => $email,
        'cpf' => $cpf,
        'phone' => $phone,
        'valor' => $valor,
        'plano' => $plano,
        'descricao' => $descricao,
        'qrcode' => $apiResponse['data']['pix_qr_code'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Salvar em arquivo JSON
    $filename = PENDING_DIR . '/' . $transactionId . '.json';
    file_put_contents($filename, json_encode($paymentData, JSON_PRETTY_PRINT));
    
    // Atualizar tracker do Telegram se houver session_id
    $sessionId = sanitize($data['session_id'] ?? '');
    if (!empty($sessionId)) {
        $trackerDir = __DIR__ . '/payments/tracker';
        $sessionFile = $trackerDir . '/' . $sessionId . '.json';
        if (file_exists($sessionFile)) {
            $sessionData = json_decode(file_get_contents($sessionFile), true);
            $msgId = $sessionData['message_id'] ?? null;
            
            if ($msgId && defined('TELEGRAM_BOT_TOKEN') && TELEGRAM_BOT_TOKEN !== 'SEU_BOT_TOKEN_AQUI') {
                $msg = "🟡 *Visitante Iniciou Checkout*\n"
                     . "📍 Local: {$sessionData['location']}\n"
                     . "👤 Nome: {$nome}\n"
                     . "💰 Plano: {$descricao} (R$ {$valor})\n"
                     . "📲 Status: Gerou PIX (Aguardando Pagamento)";
                
                $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/editMessageText";
                $postFields = http_build_query([
                    'chat_id' => TELEGRAM_CHAT_ID,
                    'message_id' => $msgId,
                    'text' => $msg,
                    'parse_mode' => 'Markdown'
                ]);
                
                $chTracker = curl_init();
                curl_setopt($chTracker, CURLOPT_URL, $url);
                curl_setopt($chTracker, CURLOPT_POST, true);
                curl_setopt($chTracker, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($chTracker, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($chTracker, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($chTracker, CURLOPT_TIMEOUT, 3);
                curl_exec($chTracker);
                curl_close($chTracker);
                
                // Salva paymentId na sessão para o webhook usar
                $sessionData['payment_id'] = $transactionId;
                file_put_contents($sessionFile, json_encode($sessionData));
            }
        }
    }
    
    // Retornar sucesso
    echo json_encode([
        'success' => true,
        'message' => 'QR Code gerado com sucesso',
        'transaction_id' => $transactionId,
        'qrcode' => $apiResponse['data']['pix_qr_code'],
        'amount' => $valor,
        'reference_code' => $transactionId
    ]);
    
} else {
    echo json_encode([
        'success' => false,
        'message' => $apiResponse['message'] ?? 'Erro ao gerar QR Code PIX',
        'details' => $apiResponse
    ]);
}
?>
