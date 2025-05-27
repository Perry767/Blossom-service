<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    error_log("Database connection failed: " . $conn->connect_error . " at " . date('Y-m-d H:i:s'));
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Verify the email matches the admin's email
    if ($email !== 'survivalperry@gmail.com') {
        $_SESSION['error'] = "Invalid email address.";
        error_log("Invalid email address attempted for password reset: $email at " . date('Y-m-d H:i:s'));
        header("Location: login.php");
        exit();
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Email not found.";
        error_log("Email not found for password reset: $email at " . date('Y-m-d H:i:s'));
        header("Location: login.php");
        exit();
    }
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $stmt->close();

    // Generate a 6-digit reset code
    $reset_code = sprintf("%06d", mt_rand(0, 999999));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Code expires in 1 hour

    // Store the reset code and expiry in the database
    $stmt = $conn->prepare("UPDATE users SET reset_code = ?, reset_expiry = ? WHERE id = ?");
    $stmt->bind_param("ssi", $reset_code, $expiry, $user_id);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to generate reset code: " . $stmt->error;
        error_log("Failed to generate reset code: " . $stmt->error . " at " . date('Y-m-d H:i:s'));
        header("Location: login.php");
        exit();
    }
    $stmt->close();

    // Send the reset code via email
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 3; // Enable verbose debug output
    $mail->Debugoutput = 'error_log'; // Log debug info to PHP error log

    try {
        // Gmail SMTP settings with SSL
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'survivalperry@gmail.com';
        $mail->Password = 'uwtl rzuc bali ihuj'; // Replace with your new App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('survivalperry@gmail.com', 'Blossom Services Admin');
        $mail->addAddress('survivalperry@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code - Blossom Services';
        $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f9f9f9;">
                <div style="text-align: center; padding-bottom: 20px;">
                    <h1 style="color: #1e3a8a; margin: 0;">Blossom Services</h1>
                    <p style="color: #666; font-size: 14px;">Professional Cleaning Solutions</p>
                </div>
                <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <h2 style="color: #1e3a8a; font-size: 20px; margin-bottom: 20px;">Password Reset Request</h2>
                    <p style="margin: 10px 0; color: #333;">You have requested to reset your password. Use the following code to reset your password:</p>
                    <h3 style="color: #1e3a8a; font-size: 24px; text-align: center; margin: 20px 0;">' . $reset_code . '</h3>
                    <p style="margin: 10px 0; color: #333;">This code will expire at ' . $expiry . '.</p>
                    <p style="margin: 10px 0; color: #333;">If you did not request a password reset, please ignore this email.</p>
                </div>
                <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0; margin-top: 20px;">
                    <p style="color: #666; font-size: 12px;">© 2025 Blossom Services. All rights reserved.</p>
                    <p style="color: #666; font-size: 12px;">Contact us at <a href="mailto:survivalperry@gmail.com" style="color: #1e3a8a; text-decoration: none;">survivalperry@gmail.com</a></p>
                </div>
            </div>';
        $mail->AltBody = "Password Reset Request\n\nYou have requested to reset your password. Use the following code to reset your password:\n\n$reset_code\n\nThis code will expire at $expiry.\n\nIf you did not request a password reset, please ignore this email.";

        $mail->send();
        error_log("Password reset code sent to survivalperry@gmail.com at " . date('Y-m-d H:i:s'));
        $_SESSION['success'] = "A reset code has been sent to your email.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send reset code: {$mail->ErrorInfo}";
        error_log("Failed to send reset code: {$mail->ErrorInfo} at " . date('Y-m-d H:i:s'));
    }

    $conn->close();
    header("Location: reset_password.php");
    exit();
}

$_SESSION['error'] = "Invalid request method.";
error_log("Invalid request method in send_reset_code.php at " . date('Y-m-d H:i:s'));
header("Location: login.php");
exit();
?>