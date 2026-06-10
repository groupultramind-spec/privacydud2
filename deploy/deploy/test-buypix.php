<?php
require 'config.php';

$postData = [
    'amount' => 15.00,
    'payer_document' => '12345678909',
    'payer_name' => 'Teste da Silva',
    'webhook_url' => WEBHOOK_URL,
    'payer_ip' => '127.0.0.1',
];

$ch = curl_init('https://buypix.me/api/v1/deposits');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . BUYPIX_API_KEY,
        'Content-Type: application/json',
        'X-Idempotency-Key: ' . uniqid(),
    ],
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response = curl_exec($ch);
echo "RESPONSE: " . $response . "\n";
