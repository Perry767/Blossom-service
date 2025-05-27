<?php
session_start();
require 'vendor/autoload.php'; // Ensure this path is correct for your Composer setup
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    header("Location: index.php#book-now");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $preferred_date = $_POST['preferred_date'] ?? '';

    // Basic server-side validation
    if (empty($name) || empty($email) || empty($phone_number) || empty($address) || empty($service_type) || empty($preferred_date)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: index.php#book-now");
        exit();
    }

    // Prepare and bind with new fields
    $stmt = $conn->prepare("INSERT INTO bookings (client_name, client_email, phone_number, address, service_type, preferred_date, reply_status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("ssssss", $name, $email, $phone_number, $address, $service_type, $preferred_date);

    if ($stmt->execute()) {
        $booking_id = $conn->insert_id;

        // Send confirmation email
        $mail = new PHPMailer(true);
        try {
            // Enable verbose debug output during development
            $mail->SMTPDebug = 0; // Remove or set to 0 in production
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use your SMTP host (e.g., smtp.gmail.com for Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'survivalperry@gmail.com'; // Replace with your email
            $mail->Password = 'uwtl rzuc bali ihuj'; // Replace with your App Password if 2FA is enabled
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('survivalperry@gmail.com', 'Blossom Services');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Booking Confirmation - Blossom Services';
            $mail->Body = "
                <h2>Booking Confirmation</h2>
                <p>Dear {$name},</p>
                <p>Thank you for booking with Blossom Services. Below are your booking details:</p>
                <ul>
                    <li><strong>Service:</strong> {$service_type}</li>
                    <li><strong>Date:</strong> {$preferred_date}</li>
                    <li><strong>Address:</strong> {$address}</li>
                    <li><strong>Phone:</strong> {$phone_number}</li>
                </ul>
                <p>We will contact you soon to confirm your booking. For any queries, reply to this email or contact us at +234 123 456 7890.</p>
                <p>Best regards,<br>Blossom Services Team</p>
            ";

            $mail->send();
            $_SESSION['success'] = 'Booking submitted successfully! A confirmation email has been sent.';
        } catch (Exception $e) {
            $_SESSION['error'] = "Booking could not be sent, Input correct Email Address";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to submit booking.";
    }

    $conn->close();
    header("Location: index.php#book-now");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: index.php#book-now");
    exit();
}
?>