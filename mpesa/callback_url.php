<?php
include '../includes/pdoconfig.php';
require '../vendor/autoload.php';

header("Content-Type: application/json");
$stkCallbackResponse = file_get_contents('php://input');
$logFile = "Mpesastkresponse.json";
$log = fopen($logFile, "a");
fwrite($log, $stkCallbackResponse);
fclose($log);

$data = json_decode($stkCallbackResponse);

$MerchantRequestID = $data->Body->stkCallback->MerchantRequestID;
$CheckoutRequestID = $data->Body->stkCallback->CheckoutRequestID;
$ResultCode = $data->Body->stkCallback->ResultCode;
$ResultDesc = $data->Body->stkCallback->ResultDesc;
$Amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$TransactionId = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$UserPhoneNumber = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;

//CHECK IF THE TRANSACTION WAS SUCCESSFUL 
if ($ResultCode == 0) {
  try {
    //STORE THE TRANSACTION DETAILS IN THE DATABASE
    $stmt = $DB_con->prepare("INSERT INTO transactions (MerchantRequestID,CheckoutRequestID,ResultCode,Amount,MpesaReceiptNumber,PhoneNumber) VALUES (:MerchantRequestID, :CheckoutRequestID, :ResultCode, :Amount, :TransactionId, :UserPhoneNumber)");
    $stmt->bindParam(':MerchantRequestID', $MerchantRequestID);
    $stmt->bindParam(':CheckoutRequestID', $CheckoutRequestID);
    $stmt->bindParam(':ResultCode', $ResultCode);
    $stmt->bindParam(':Amount', $Amount);
    $stmt->bindParam(':TransactionId', $TransactionId);
    $stmt->bindParam(':UserPhoneNumber', $UserPhoneNumber);
    $stmt->execute();

    // SEND EMAIL
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $userEmailQuery = $DB_con->prepare("SELECT email FROM userregistration WHERE ContactNo = :UserPhoneNumber");
    $userEmailQuery->bindParam(':UserPhoneNumber', $UserPhoneNumber);
    $userEmailQuery->execute();
    $userEmailResult = $userEmailQuery->fetch(PDO::FETCH_ASSOC);
    $userEmail = $userEmailResult['email'];

    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '680465db003b51';
    $mail->Password = '670b40992a6128';
    $mail->Port = 2525;

    // Set email parameters
    $mail->setFrom('lumumbaharmony@gmail.com', 'Harmony');
    $mail->addAddress($userEmail);
    $mail->Subject = 'Payment Confirmation';
    $mail->Body = 'Your payment of $' . $Amount . ' was successful. Thank you!';

    // Send the email
    if ($mail->send()) {
      echo 'Email sent successfully!';
    } else {
      throw new Exception('Error sending email: ' . $mail->ErrorInfo);
    }
  } catch (Exception $e) {
    // Log the error for debugging purposes
    error_log('Error: ' . $e->getMessage(), 0);
    echo 'An error occurred. Please try again later.';
  }
}