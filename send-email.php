<?php 
$name = "Justus Otundo";
$email = "justusotundo86@gmail.com";
$subject = "NEW MAIL";
$Message = "You have registered for the membership of the hostels.Thank you";

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'sandbox.smtp.mailtrap.io';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 2525;
$phpmailer->Username = '66927f37867fce';
$phpmailer->Password = '8bacdf153faac5';

$phpmailer->setFrom('justusotundo86@gmail.com', 'Justus Otundo');
$phpmailer->addAddress($email, $name);
$phpmailer->Subject = $subject;
$phpmailer->Body = $message;

if ($phpmailer->send()) {
    echo 'Email has been sent';
} else {
    echo 'Email could not be sent';
}
?>
