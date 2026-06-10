<?php
date_default_timezone_set('America/Sao_Paulo');
/**
 * Configurações do Sistema de Pagamento PIX
 * Ecompag API Integration
 */

// Caminho do arquivo de configuração seguro (salvo fora do git)
$secureConfigFile = __DIR__ . '/config_secure.json';

// Valores padrão
$configDb = [
    'BUYPIX_API_KEY' => 'bpx_8NHZUlae4L5ykZsdFGIXyFmryV6wpB0X2M0wWuN0',
    'BUYPIX_WEBHOOK_SECRET' => 'whsec_sua_secret_aqui',
    'TELEGRAM_BOT_TOKEN' => '8236778290:AAGXUQWm-D3lCoOAch7cgMEf-b4mm4XZ5Mk',
    'TELEGRAM_CHAT_ID' => '-1003902287618',
    'EMAIL_FROM' => 'seuemailaqui@site.com',
    'EMAIL_FROM_NAME' => 'Privacy - Eduarda',
    'EMAIL_SUBJECT' => 'Pagamento Confirmado - Acesso Liberado!',
    'SMTP_HOST' => 'smtp.hostinger.com',
    'SMTP_PORT' => 587,
    'SMTP_USER' => 'seuemailaqui@site.com',
    'SMTP_PASSWORD' => 'suasenhaaqui',
    'ACCESS_LINK' => 'https://t.me/+Jxws5Mi7ZgFlYjJh',
    'ACCESS_TOKEN_SALT' => 'Pr1v@cyEdu4rd@2026#xK9'
];

if (file_exists($secureConfigFile)) {
    $loaded = json_decode(file_get_contents($secureConfigFile), true);
    if (is_array($loaded)) {
        $configDb = array_merge($configDb, $loaded);
    }
} else {
    // Salva o inicial para garantir que o arquivo exista
    file_put_contents($secureConfigFile, json_encode($configDb, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Credenciais BuyPix
define('BUYPIX_API_KEY', $configDb['BUYPIX_API_KEY']);
define('BUYPIX_WEBHOOK_SECRET', $configDb['BUYPIX_WEBHOOK_SECRET']);

// Configurações do Telegram Bot
define('TELEGRAM_BOT_TOKEN', $configDb['TELEGRAM_BOT_TOKEN']);
define('TELEGRAM_CHAT_ID', $configDb['TELEGRAM_CHAT_ID']);
define('ACCESS_LINK', $configDb['ACCESS_LINK']);
define('ACCESS_TOKEN_SALT', $configDb['ACCESS_TOKEN_SALT']);

// Configurações de Email
define('EMAIL_FROM', $configDb['EMAIL_FROM']);
define('EMAIL_FROM_NAME', $configDb['EMAIL_FROM_NAME']);
define('EMAIL_SUBJECT', $configDb['EMAIL_SUBJECT']);

// Configurações SMTP
define('SMTP_HOST', $configDb['SMTP_HOST']);
define('SMTP_PORT', intval($configDb['SMTP_PORT']));
define('SMTP_USER', $configDb['SMTP_USER']);
define('SMTP_PASSWORD', $configDb['SMTP_PASSWORD']);

// URL do site detectada automaticamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', rtrim($protocol . '://' . $host, '/'));
define('WEBHOOK_URL', SITE_URL . '/webhook.php');

// Diretórios de armazenamento
define('PAYMENTS_DIR', __DIR__ . '/payments');
define('PENDING_DIR', PAYMENTS_DIR . '/pending');
define('PAID_DIR', PAYMENTS_DIR . '/paid');

// Criar diretórios se não existirem
if (!file_exists(PAYMENTS_DIR)) mkdir(PAYMENTS_DIR, 0755, true);
if (!file_exists(PENDING_DIR))  mkdir(PENDING_DIR,  0755, true);
if (!file_exists(PAID_DIR))     mkdir(PAID_DIR,     0755, true);

// Função para gerar ID único
function generateUniqueId() {
    return uniqid('payment_', true) . '_' . time();
}

// Função para sanitizar dados
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) return false;
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;
    
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Função para validar Email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}