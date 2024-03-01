<?php
// No need to start session or require pdoconfig.php here

if (isset($_POST['submit'])) {
    date_default_timezone_set('Africa/Nairobi');

    # Access token
    $consumerKey = 'ajMuhsGcUJXAGADYCtmSZgJdFIcj9wa0CKpCS1GBTSs9lzxy';
    $consumerSecret = 'uxHiRgEiU51BeqxiassaLVajLqoyx7avBynMRAAtj9b50SdrCRGZggefhHbv24xS';

    # Define the variables
    # Provide the following details, this part is found on your test credentials on the developer account
    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

    /*
    This are your info, for
    $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
    $AccountReference, it may be invoice number, account number, etc., on production systems, but for a test just put anything
    TransactionDesc can be anything, probably a better description of or the transaction
    $Amount this is the total invoiced amount, Any amount here will be 
    actually deducted from a client's side/your test phone number once the PIN has been entered to authorize the transaction. 
    for developer/test accounts, this money will be reversed automatically by midnight.
    */

    $PartyA = $_POST['phone']; // This is your phone number, 
    $AccountReference = '2255';
    $TransactionDesc = 'Test Payment';
    $Amount = $_POST['amount'];

    # Get the timestamp, format YYYYmmddhms -> 20181004151020
    $Timestamp = date('YmdHis');

    # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

    # Header for access token
    $headers = ['Content-Type:application/json; charset=utf8'];

    # M-PESA endpoint urls
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    # Callback URL
    $CallBackURL = 'https://mydomain.com/callback_url.php';

    // Function to get access token
    function getAccessToken($consumerKey, $consumerSecret)
    {
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
        $headers = array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/json'
        );
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data['access_token'];
    }

    // Function to initiate payment
    function initiatePayment($accessToken, $BusinessShortCode, $Passkey, $PhoneNumber, $Amount, $CallBackURL, $PartyA, $AccountReference, $TransactionDesc)
    {
        $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $timestamp = date('YmdHis');
        $password = base64_encode($BusinessShortCode . $Passkey . $timestamp);
        $data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
        );
        $headers = array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        );
        $options = array(
            CURLOPT_URL => $initiate_url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true
        );
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        // Parse JSON response and return as an object
        return json_decode($response);
    }

    // Get access token
    $accessToken = getAccessToken($consumerKey, $consumerSecret);

    // Initiate payment
    $paymentResponse = initiatePayment($accessToken, $BusinessShortCode, $Passkey, $PartyA, $Amount, $CallBackURL, $PartyA, $AccountReference, $TransactionDesc);

    // Echo response to the user
    echo "Payment initiated successfully. Please wait for confirmation.";
}
