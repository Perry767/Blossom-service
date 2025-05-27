<?php
session_start();

// Initialize variables
$errors = [];
$success = false;

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = isset($_POST['service']) ? trim($_POST['service']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time = isset($_POST['time']) ? trim($_POST['time']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    // Validation
    if (empty($service)) {
        $errors[] = 'Service is required.';
    }
    if (empty($date)) {
        $errors[] = 'Date is required.';
    }
    if (empty($time)) {
        $errors[] = 'Time is required.';
    }
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if (empty($phone) || !preg_match('/^\+?\d{10,15}$/', $phone)) {
        $errors[] = 'A valid phone number is required.';
    }

    // If no errors, process booking
    if (empty($errors)) {
        // Sanitize inputs
        $booking = [
            'service' => htmlspecialchars($service),
            'date' => htmlspecialchars($date),
            'time' => htmlspecialchars($time),
            'name' => htmlspecialchars($name),
            'email' => htmlspecialchars($email),
            'phone' => htmlspecialchars($phone),
            'status' => 'Pending'
        ];

        // Load existing bookings
        $bookingsFile = 'bookings.json';
        $bookings = file_exists($bookingsFile) ? json_decode(file_get_contents($bookingsFile), true) : [];
        $bookings[] = $booking;

        // Save to bookings.json
        if (file_put_contents($bookingsFile, json_encode($bookings, JSON_PRETTY_PRINT))) {
            $success = true;
            $_SESSION['booking_message'] = 'Booking submitted successfully!';
            
            // Simulate email notification to admin (replace with real email service in production)
            $adminEmail = 'admin@cleanproservices.co.uk';
            $subject = 'New Booking Submitted';
            $message = "New booking by {$booking['name']} for {$booking['service']} on {$booking['date']} at {$booking['time']}.\nEmail: {$booking['email']}\nPhone: {$booking['phone']}";
            // mail($adminEmail, $subject, $message); // Uncomment with proper email setup
        } else {
            $errors[] = 'Failed to save booking. Please try again.';
        }
    }

    // Store errors in session if any
    if (!empty($errors)) {
        $_SESSION['booking_errors'] = $errors;
    }
}

// Redirect back to index.html booking section
header('Location: index.html#booking');
exit;
?>