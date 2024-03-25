<?php
// Include the access token file
include 'accessToken.php';

date_default_timezone_set('Africa/Nairobi');

// Define API endpoints and parameters
$processRequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callbackUrl = 'https://e6f5-102-217-157-219.ngrok-free.app/HostelManagement/mpesa/callback_url.php';
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$businessShortCode = '174379';
$timestamp = date('YmdHis');
$phone = $_POST['phone'] ?? ''; // Phone number to receive the STK push
$money = $_POST['amount'] ?? ''; // Amount of money
$partyA = $phone;
$partyB = '254708374149';
$accountReference = 'Oscars Hostel Management';
$transactionDesc = 'Mpesa Test';
$amount = $money;

// Encrypt data to get password
$password = base64_encode($businessShortCode . $passkey . $timestamp);

// Prepare request data
$requestData = [
    'BusinessShortCode' => $businessShortCode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $partyA,
    'PartyB' => $businessShortCode,
    'PhoneNumber' => $partyA,
    'CallBackURL' => $callbackUrl,
    'AccountReference' => $accountReference,
    'TransactionDesc' => $transactionDesc
];

$requestJson = json_encode($requestData);

// Set custom headers
$stkPushHeaders = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
];

// Initiate cURL request
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $processRequestUrl,
    CURLOPT_HTTPHEADER => $stkPushHeaders,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $requestJson
]);

// Execute cURL request and handle response
$response = curl_exec($curl);

if ($response === false) {
    // Handle cURL error
    echo 'cURL Error: ' . curl_error($curl);
    exit;
}

// Decode response
$responseData = json_decode($response);

if ($responseData && isset($responseData->ResponseCode) && $responseData->ResponseCode == "0") {
    echo "The CheckoutRequestID for this transaction is: " . $responseData->CheckoutRequestID;
} else {
    echo "Failed to initiate STK push transaction.";
}

// Close cURL session
curl_close($curl);