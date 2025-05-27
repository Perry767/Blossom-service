<?php
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php#bookings");
} else {
    echo "Error deleting booking.";
}

$stmt->close();
$conn->close();
?>