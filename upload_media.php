<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    header("Location: admin.php?error=Database connection failed");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle Before Media
    $before_file = $_FILES['before_media'];
    $before_filename = time() . "_before_" . basename($before_file['name']);
    $before_path = $upload_dir . $before_filename;
    $before_media_type = (pathinfo($before_file['name'], PATHINFO_EXTENSION) === 'mp4') ? 'video' : 'image';

    // Handle After Media
    $after_file = $_FILES['after_media'];
    $after_filename = time() . "_after_" . basename($after_file['name']);
    $after_path = $upload_dir . $after_filename;
    $after_media_type = (pathinfo($after_file['name'], PATHINFO_EXTENSION) === 'mp4') ? 'video' : 'image';

    // Validate media types match
    if ($before_media_type !== $after_media_type) {
        header("Location: admin.php?error=Before and After media types must match (both images or both videos)");
        exit();
    }

    // Move files
    if (move_uploaded_file($before_file['tmp_name'], $before_path) && move_uploaded_file($after_file['tmp_name'], $after_path)) {
        $stmt = $conn->prepare("INSERT INTO gallery (before_image, after_image, description, media_type, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $before_path, $after_path, $description, $before_media_type);
        if ($stmt->execute()) {
            header("Location: admin.php?success=Media uploaded successfully");
        } else {
            header("Location: admin.php?error=Failed to save media to database");
        }
        $stmt->close();
    } else {
        header("Location: admin.php?error=Failed to upload media files");
    }
}

$conn->close();
?>