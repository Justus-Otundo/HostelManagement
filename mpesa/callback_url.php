<?php
include '../includes/pdoconfig.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set content type to JSON
header("Content-Type: application/json");

// Get the STK callback response
$stkCallbackResponse = file_get_contents('php://input');

// Log the STK callback response
$logFile = "Mpesastkresponse.json";
file_put_contents($logFile, $stkCallbackResponse . PHP_EOL, FILE_APPEND);

// Decode the JSON data
$data = json_decode($stkCallbackResponse);

// Extract relevant data
$stkCallback = $data->Body->stkCallback;
$MerchantRequestID = $stkCallback->MerchantRequestID;
$CheckoutRequestID = $stkCallback->CheckoutRequestID;
$ResultCode = $stkCallback->ResultCode;
$ResultDesc = $stkCallback->ResultDesc;
$CallbackMetadata = $stkCallback->CallbackMetadata->Item;
$Amount = $CallbackMetadata[0]->Value;
$TransactionId = $CallbackMetadata[1]->Value;
$UserPhoneNumber = $CallbackMetadata[4]->Value;

// Check if the transaction was successful 
if ($ResultCode == 0) {
  try {
    // Store the transaction details in the database
    $stmt = $DB_con->prepare("INSERT INTO transactions (MerchantRequestID,CheckoutRequestID,ResultCode,Amount,MpesaReceiptNumber,PhoneNumber) VALUES (:MerchantRequestID, :CheckoutRequestID, :ResultCode, :Amount, :TransactionId, :UserPhoneNumber)");
    $stmt->bindParam(':MerchantRequestID', $MerchantRequestID);
    $stmt->bindParam(':CheckoutRequestID', $CheckoutRequestID);
    $stmt->bindParam(':ResultCode', $ResultCode);
    $stmt->bindParam(':Amount', $Amount);
    $stmt->bindParam(':TransactionId', $TransactionId);
    $stmt->bindParam(':UserPhoneNumber', $UserPhoneNumber);
    $stmt->execute();

    // Fetch user email
    $userEmailQuery = $DB_con->prepare("SELECT email FROM userregistration WHERE ContactNo = :UserPhoneNumber");
    $userEmailQuery->bindParam(':UserPhoneNumber', $UserPhoneNumber);
    $userEmailQuery->execute();
    $userEmailResult = $userEmailQuery->fetch(PDO::FETCH_ASSOC);
    $userEmail = $userEmailResult['email'];

    // Send email confirmation
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '680465db003b51';
    $mail->Password = '680465db003b51';
    $mail->Port = 2525;
    $mail->setFrom('lumumbaharmony@gmail.com', 'Harmony');
    $mail->addAddress($userEmail);
    $mail->Subject = 'Payment Confirmation';
    $mail->Body = 'Your payment of $' . $Amount . ' was successful. Thank you!';

    // Send the email
    if ($mail->send()) {
      echo json_encode(['status' => 'success', 'message' => 'Email sent successfully!']);
    } else {
      throw new Exception('Error sending email: ' . $mail->ErrorInfo);
    }
  } catch (Exception $e) {
    // Log the error for debugging purposes
    error_log('Error: ' . $e->getMessage(), 0);
    echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Transaction was not successful.']);
}
