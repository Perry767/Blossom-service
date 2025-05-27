<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'survivalperry@gmail.com';
    $mail->Password = 'uwtl rzuc bali ihuj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('no-reply@cleanpro.com', 'Blossom Service');
    $mail->addAddress('survivalperry@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email.';

    $mail->send();
    echo 'Email sent!';
} catch (Exception $e) {
    echo 'Error: ' . $mail->ErrorInfo;
}
?>