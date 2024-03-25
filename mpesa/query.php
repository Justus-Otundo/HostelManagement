<?php
// Include access token file
include 'accessToken.php';

date_default_timezone_set('Africa/Nairobi');

$query_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
$BusinessShortCode = '174379';
$Timestamp = date('YmdHis');
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";

// Encrypt data to get password
$Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
// Unique ID generated when STK request initiated successfully
$CheckoutRequestID = 'ws_CO_03072023054410314768168060';

$queryheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

// Initiate the transaction
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $query_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $queryheader); // Setting custom header
$curl_post_data = array(
  'BusinessShortCode' => $BusinessShortCode,
  'Password' => $Password,
  'Timestamp' => $Timestamp,
  'CheckoutRequestID' => $CheckoutRequestID
);
$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($http_status !== 200) {
  // Handle request error
  http_response_code($http_status);
  echo json_encode(["error" => "Failed to query transaction."]);
  exit;
}

$data_to = json_decode($curl_response);

if (isset($data_to->ResultCode)) {
  switch ($data_to->ResultCode) {
    case '0':
      $message = "The transaction is successful";
      break;
    case '1032':
      $message = "Transaction has been cancelled by the user";
      break;
    case '1':
      $message = "The balance is insufficient for the transaction";
      break;
    case '1037':
       $message = "Timeout in completing transaction";
      break;
    default:
      $message = "Unknown error occurred";
  }
} else {
  $message = "Error: ResultCode not found in response";
}

echo $message;