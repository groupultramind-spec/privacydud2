<?php
require_once 'config.php';

// Diretório para salvar sessões do tracker
define('TRACKER_DIR', __DIR__ . '/payments/tracker');
if (!file_exists(TRACKER_DIR)) mkdir(TRACKER_DIR, 0755, true);

// Função para enviar requisição para API do Telegram
function telegramRequest($method, $data) {
    if (empty(TELEGRAM_BOT_TOKEN) || TELEGRAM_BOT_TOKEN === 'SEU_BOT_TOKEN_AQUI') return false;
    
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/" . $method;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Obter IP real
function getClientIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    return $ip;
}

// Buscar localização por IP
function getGeolocation($ip) {
    // Para localhost não funciona, usaremos um mock
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return ['status' => 'success', 'city' => 'Localhost', 'regionName' => 'Local', 'isp' => 'Desenvolvimento'];
    }
    
    $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,isp");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Handler da requisição HTTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['action']) || empty($data['session_id'])) {
        http_response_code(400);
        exit;
    }
    
    $action = $data['action'];
    $sessionId = sanitize($data['session_id']);
    $sessionFile = TRACKER_DIR . '/' . $sessionId . '.json';
    
    if ($action === 'enter') {
        $ip = getClientIp();
        $geo = getGeolocation($ip);
        
        $location = "Desconhecido";
        if (isset($geo['status']) && $geo['status'] === 'success') {
            $location = "{$geo['city']} / {$geo['regionName']} ({$geo['isp']})";
        }
        
        $msg = "🟢 *Novo Visitante*\n"
             . "📍 Local: {$location}\n"
             . "🌐 IP: `{$ip}`\n"
             . "⏱ Entrou às: " . date('H:i:s');
             
        $res = telegramRequest('sendMessage', [
            'chat_id' => TELEGRAM_CHAT_ID,
            'text' => $msg,
            'parse_mode' => 'Markdown'
        ]);
        
        if (isset($res['ok']) && $res['ok']) {
            $sessionData = [
                'ip' => $ip,
                'location' => $location,
                'message_id' => $res['result']['message_id'],
                'entered_at' => time()
            ];
            file_put_contents($sessionFile, json_encode($sessionData));
        }
        
        echo json_encode(['success' => true]);
        
    } elseif ($action === 'leave') {
        if (file_exists($sessionFile)) {
            $sessionData = json_decode(file_get_contents($sessionFile), true);
            $msgId = $sessionData['message_id'];
            $duration = time() - $sessionData['entered_at'];
            
            // Format duration
            $durStr = ($duration < 60) ? "{$duration} segundos" : floor($duration/60) . " minutos e " . ($duration%60) . " segundos";
            
            $msg = "🔴 *Visitante Saiu*\n"
                 . "📍 Local: {$sessionData['location']}\n"
                 . "🌐 IP: `{$sessionData['ip']}`\n"
                 . "⏱ Ficou: {$durStr} no site.";
                 
            telegramRequest('editMessageText', [
                'chat_id' => TELEGRAM_CHAT_ID,
                'message_id' => $msgId,
                'text' => $msg,
                'parse_mode' => 'Markdown'
            ]);
            
            // Apaga arquivo para limpar espaço (opcional, pode manter para logs)
            // unlink($sessionFile);
        }
        echo json_encode(['success' => true]);
    }
}
?>
