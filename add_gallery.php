<?php
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$before_image = $target_dir . basename($_FILES["before_image"]["name"]);
$after_image = $target_dir . basename($_FILES["after_image"]["name"]);
$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

if (move_uploaded_file($_FILES["before_image"]["tmp_name"], $before_image) && move_uploaded_file($_FILES["after_image"]["tmp_name"], $after_image)) {
    $stmt = $conn->prepare("INSERT INTO gallery (before_image, after_image, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $before_image, $after_image, $description);
    if ($stmt->execute()) {
        header("Location: admin.php#gallery");
    } else {
        echo "Error adding gallery images.";
    }
    $stmt->close();
} else {
    echo "Error uploading images.";
}

$conn->close();
?>