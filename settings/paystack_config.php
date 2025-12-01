<?php
// API Keys (Test Keys from your uploaded file)
define('PAYSTACK_SECRET_KEY', 'sk_test_d8197eed588728fe89a884be7f9932f47b66c425'); 
define('PAYSTACK_PUBLIC_KEY', 'pk_test_f5348ad1cc2199479ca67ec0da4f9501f62db8fd');

// Endpoints
define('PAYSTACK_INIT_URL', 'https://api.paystack.co/transaction/initialize');
define('PAYSTACK_VERIFY_URL', 'https://api.paystack.co/transaction/verify/');

// Helpers
function paystack_init($email, $amount, $reference, $callback_url) {
    $url = PAYSTACK_INIT_URL;
    $fields = [
        'email' => $email,
        'amount' => $amount * 100, // Convert to pesewas
        'reference' => $reference,
        'callback_url' => $callback_url,
        'currency' => 'GHS'
    ];

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
        "Cache-Control: no-cache",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only, be careful in production
    
    $result = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        error_log('Paystack curl error: ' . $curl_error);
        return ['status' => false, 'message' => 'cURL error: ' . $curl_error];
    }
    
    $decoded = json_decode($result, true);
    error_log('Paystack response: ' . print_r($decoded, true));
    
    return $decoded;
}

function paystack_verify($reference) {
    $url = PAYSTACK_VERIFY_URL . rawurlencode($reference);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
        "Cache-Control: no-cache"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($result, true);
}
?>