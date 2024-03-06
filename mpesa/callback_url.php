<?php
header("Content-Type: application/json");

// Default response to M-Pesa
$response = '{
    "ResultCode": 0, 
    "ResultDesc": "Confirmation Received Successfully"
}';

// Receive M-Pesa response data
$mpesaResponse = file_get_contents('php://input');

// Log the M-Pesa response to a file
$logFile = "M_PESAConfirmationResponse.txt";
$log = fopen($logFile, "a");
fwrite($log, $mpesaResponse . "\n");
fclose($log);

// Decode the JSON response from M-Pesa
$data = json_decode($mpesaResponse, true);

// Extract relevant information from the M-Pesa response
$resultCode = isset($data['ResultCode']) ? $data['ResultCode'] : null;
$resultDesc = isset($data['ResultDesc']) ? $data['ResultDesc'] : null;
$transactionId = isset($data['TransactionID']) ? $data['TransactionID'] : null;
$amount = isset($data['Amount']) ? $data['Amount'] : null;
$phoneNumber = isset($data['PhoneNumber']) ? $data['PhoneNumber'] : null;

// Check if the transaction was successful
if ($resultCode == 0) {
    // Transaction was successful, update database with transaction details
    try {
        // Connect to the database (assuming you have already included pdoconfig.php)
        require('../includes/pdoconfig.php');

        // Prepare SQL statement to insert transaction details into the database
        $sql = "INSERT INTO transactions (transaction_code, amount, phone) VALUES (:transaction_id, :amount, :phone_number)";

        // Prepare and execute SQL statement
        $stmt = $DB_con->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':phone_number', $phoneNumber);
        $stmt->execute();

        // Log success
        echo $response;
    } catch (PDOException $e) {
        // Log database error
        echo "Error: " . $e->getMessage();
    }
} else {
    // Transaction failed, log the failure and return appropriate response
    echo $response;
}
