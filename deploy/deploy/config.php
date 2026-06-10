<?php
date_default_timezone_set('America/Sao_Paulo');
/**
 * Configurações do Sistema de Pagamento PIX
 * Ecompag API Integration
 */

// Credenciais BuyPix
define('BUYPIX_API_KEY', 'bpx_8NHZUlae4L5ykZsdFGIXyFmryV6wpB0X2M0wWuN0');
define('BUYPIX_WEBHOOK_SECRET', 'whsec_sua_secret_aqui');

// Configurações do Telegram Bot
define('TELEGRAM_BOT_TOKEN', '8236778290:AAGXUQWm-D3lCoOAch7cgMEf-b4mm4XZ5Mk'); // Token gerado no BotFather
define('TELEGRAM_CHAT_ID', '-1003902287618'); // Seu ID do Telegram

// URL do site detectada automaticamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', rtrim($protocol . '://' . $host, '/'));
define('WEBHOOK_URL', SITE_URL . '/webhook.php');

// Diretórios de armazenamento
define('PAYMENTS_DIR', __DIR__ . '/payments');
define('PENDING_DIR', PAYMENTS_DIR . '/pending');
define('PAID_DIR', PAYMENTS_DIR . '/paid');

// Configurações de Email
define('EMAIL_FROM', 'seuemailaqui@site.com');
define('EMAIL_FROM_NAME', 'Privacy - Eduarda');
define('EMAIL_SUBJECT', 'Pagamento Confirmado - Acesso Liberado!');

// Configurações SMTP
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'seuemailaqui@site.com');
define('SMTP_PASSWORD', 'suasenhaaqui');

// Link de Acesso ao Conteúdo
define('ACCESS_LINK', 'https://t.me/+Jxws5Mi7ZgFlYjJh');

// Salt para validação do token de acesso
define('ACCESS_TOKEN_SALT', 'Pr1v@cyEdu4rd@2026#xK9');

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