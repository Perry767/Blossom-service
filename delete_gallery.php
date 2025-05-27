<?php
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$result = $conn->query("SELECT before_image, after_image FROM gallery WHERE id = $id");
$row = $result->fetch_assoc();
unlink($row['before_image']);
unlink($row['after_image']);

$stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php#gallery");
} else {
    echo "Error deleting gallery image.";
}

$stmt->close();
$conn->close();
?>