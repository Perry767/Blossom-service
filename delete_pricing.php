<?php
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$stmt = $conn->prepare("DELETE FROM pricing WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php#pricing");
} else {
    echo "Error deleting pricing.";
}

$stmt->close();
$conn->close();
?>