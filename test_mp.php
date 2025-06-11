<?php
$token = 'TEST-3396948188622078-041622-0fabb473ded71d0270720c4b4d0453fd-113074611';
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'items' => [[
            'title' => 'Prueba desde PHP',
            'quantity' => 1,
            'currency_id' => 'USD',
            'unit_price' => 10.00
        ]],
        'back_urls' => [
            'success' => 'https://retayatecancun.com/exito',
            'failure' => 'https://retayatecancun.com/fallo',
            'pending' => 'https://retayatecancun.com/pendiente'
        ],
        'auto_return' => 'approved',
        'external_reference' => 'TEST-123'
    ])
]);

$response = curl_exec($curl);
$data = json_decode($response, true);
curl_close($curl);

echo "<pre>";
print_r($data);
echo "</pre>";