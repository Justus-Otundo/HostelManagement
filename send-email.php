<?php
$name = "Justus Otundo";
$email = "justusotundo86@gmail.com";
$subject = "NEW MAIL";
$message = "You have been registered for the membership of the Oscar hostels. Thank you!\nHere are your credentials to login into your account.\nVisit https://e10c-102-217-157-219.ngrok-free.app/HostelManagement/index.php \n\n";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require "vendor/autoload.php";

function mailing()
{
    global $subject, $message; // Making $subject and $message accessible within the function

    // Check if required fields are set in $_POST
    if (!isset($_POST['email']) || !isset($_POST['fname'])) {
        return false; 
    }

    // Retrieve email and password from the database based on the user's registration
    $userEmail = $_POST['email'];
    $userPassword = retrieveUserPasswordFromDatabase(); // Replace this with your actual database query

    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '66927f37867fce';
    $phpmailer->Password = '8bacdf153faac5';

    $phpmailer->setFrom('justusotundo86@gmail.com', 'Justus Otundo');
    $phpmailer->addAddress($_POST['email'], $_POST['fname']);
    $phpmailer->Subject = $subject; // Using $subject directly

    // Construct the email body including the user's email and password
    $phpmailer->Body = "You have been registered for the membership of the Oscar hostels. Thank you!\nHere are your credentials to login into your account.\nVisit <a href='https://e10c-102-217-157-219.ngrok-free.app/HostelManagement/index.php'>Hostel Management System</a>\nVisit <a href ='https://10015.io/tools/md5-encrypt-decrypt'>md5</>\nmd5 is for password decryption\n\n";
    $phpmailer->Body .= "Email: " . $userEmail . "\n";
    $phpmailer->Body .= "Password: " . $userPassword . "\n";
    if ($phpmailer->send()) {
        return true; // Email has been sent successfully
    } else {
        return false; // Email could not be sent
    }
}

function retrieveUserPasswordFromDatabase()
{
    // Replace with your actual database connection details
    $host = 'localhost';
    $dbname = 'hostelmsphp';
    $username = 'root';
    $password = '';

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Prepare and execute the database query
    $userEmail = $_POST['email'];
    $stmt = $pdo->prepare("SELECT password FROM userregistration WHERE email = :email");
    $stmt->bindParam(':email', $userEmail);
    $stmt->execute();

    // Fetch the password from the query result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $password = $result['password'];

    return $password;
}
?>