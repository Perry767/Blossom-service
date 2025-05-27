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
    $client_name = filter_var($_POST['client_name'], FILTER_SANITIZE_STRING);
    $review_text = filter_var($_POST['review_text'], FILTER_SANITIZE_STRING);
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT);

    // Validate inputs
    if (empty($client_name) || empty($review_text) || !$rating || $rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Please fill in all required fields correctly.";
        header("Location: admin.php#reviews");
        exit();
    }

    // Handle file upload
    $client_image = null;
    if (!empty($_FILES['client_image']['name'])) {
        $target_dir = "uploads/reviews/";
        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $target_file = $target_dir . basename($_FILES["client_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the file is an image
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["client_image"]["tmp_name"], $target_file)) {
                $client_image = $target_file;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: admin.php#reviews");
                exit();
            }
        } else {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: admin.php#reviews");
            exit();
        }
    }

    // Insert the review into the database
    $stmt = $conn->prepare("INSERT INTO reviews (client_name, review_text, rating, client_image, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssis", $client_name, $review_text, $rating, $client_image);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Review added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add review: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: admin.php#reviews");
    exit();
}

$_SESSION['error'] = "Invalid request method.";
header("Location: admin.php#reviews");
exit();
?>