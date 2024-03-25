<?php
// Your M-Pesa API keys
define('CONSUMER_KEY', 'Xi0cNoGTCUtkWFj1khb0zYsSHgRcANtxVxgOAIMqAJauNT4N');
define('CONSUMER_SECRET', 'vw685d3NLZcqdwveUZAanihI3VDJM1lAgunYEeau4mCOfRAiCTOdIE1iGyIjWsfY');

// Function to retrieve access token
function getAccessToken() {
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $credentials = CONSUMER_KEY . ':' . CONSUMER_SECRET;
    $auth_header = 'Authorization: Basic ' . base64_encode($credentials);

    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $auth_header]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($status !== 200) {
        // Handle error
        return false;
    }

    $result = json_decode($result);
    return $result->access_token;
}

// Get access token
$access_token = getAccessToken();
if ($access_token) {
    echo $access_token;
} else {
    echo "Failed to obtain access token.";
}
