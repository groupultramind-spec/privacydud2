<?php
require 'config.php';
$postData = [
    'amount' => 15.00,
    'payer_document' => '12345678909',
    'payer_name' => 'Teste da Silva',
    'webhook_url' => WEBHOOK_URL,
    'payer_ip' => '127.0.0.1',
];
function generateUUID() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
$ch = curl_init('https://buypix.me/api/v1/deposits');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . BUYPIX_API_KEY,
        'Content-Type: application/json',
        'X-Idempotency-Key: ' . generateUUID(),
    ],
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_SSL_VERIFYPEER => false,
]);
$response = curl_exec($ch);
echo $response;
