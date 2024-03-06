<?php
include '../includes/pdoconfig.php';
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
  //STORE THE TRANSACTION DETAILS IN THE DATABASE
  $stmt = $DB_con->prepare("INSERT INTO transactions (MerchantRequestID,CheckoutRequestID,ResultCode,Amount,MpesaReceiptNumber,PhoneNumber) VALUES (:MerchantRequestID, :CheckoutRequestID, :ResultCode, :Amount, :TransactionId, :UserPhoneNumber)");
  $stmt->bindParam(':MerchantRequestID', $MerchantRequestID);
  $stmt->bindParam(':CheckoutRequestID', $CheckoutRequestID);
  $stmt->bindParam(':ResultCode', $ResultCode);
  $stmt->bindParam(':Amount', $Amount);
  $stmt->bindParam(':TransactionId', $TransactionId);
  $stmt->bindParam(':UserPhoneNumber', $UserPhoneNumber);
  $stmt->execute();
}
