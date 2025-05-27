<?php
session_start();
require 'vendor/autoload.php'; // Use this if Composer is used
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$booking_id = $_GET['id'] ?? null;
if ($booking_id) {
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if ($booking) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message'])) {
            $reply_message = $_POST['reply_message'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'survivalperry@gmail.com'; // Set your email
                $mail->Password = 'uwtl rzuc bali ihuj'; // Use App Password if 2FA is enabled
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('survivalperry@gmail.com', 'Blossom Services');
                $mail->addAddress($booking['client_email'], $booking['client_name']);
                $mail->isHTML(true);
                $mail->Subject = 'Booking Reply - Blossom Services';
                $mail->Body = nl2br($reply_message);

                $mail->send();
                $stmt = $conn->prepare("UPDATE bookings SET reply_status = 'replied' WHERE id = ?");
                $stmt->bind_param("i", $booking_id);
                $stmt->execute();
                $stmt->close();
                $_SESSION['success'] = 'Reply sent successfully!';
            } catch (Exception $e) {
                $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            header("Location: admin.php#bookings");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Booking not found.';
        header("Location: admin.php#bookings");
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid booking ID.';
    header("Location: admin.php#bookings");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply Booking - CleanPro Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Reply to Booking</h2>
        <?php if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>'; unset($_SESSION['error']); } ?>
        <?php if (isset($_SESSION['success'])) { echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>'; unset($_SESSION['success']); } ?>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="reply_message" class="form-label">Reply Message</label>
                <textarea name="reply_message" id="reply_message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Reply</button>
            <a href="admin.php#bookings" class="btn btn-secondary ms-2">Back</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>