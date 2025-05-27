<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_type = filter_var($_POST['service_type'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);

    // Validate inputs
    if (empty($service_type) || empty($description) || !$price || $price < 0) {
        $_SESSION['error'] = "Please fill in all required fields correctly.";
        header("Location: admin.php#pricing");
        exit();
    }

    // Insert the pricing into the database
    $stmt = $conn->prepare("INSERT INTO pricing (service_type, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $service_type, $description, $price);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pricing added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add pricing: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin.php#pricing");
    exit();
}

$_SESSION['error'] = "Invalid request method.";
header("Location: admin.php#pricing");
exit();
?>