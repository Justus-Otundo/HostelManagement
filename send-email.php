<?php

$name = "Justus Otundo";
$email = "justusotundo86@gmail.com";
$subject = "NEW MAIL";
$message = "You have registered for the membership of the hostels. Thank you";

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
    $phpmailer->Body = $message; // Using $message directly

    if ($phpmailer->send()) {
        return true; // Email has been sent successfully
    } else {
        return false; // Email could not be sent
    }
}
