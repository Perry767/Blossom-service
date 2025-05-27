<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            // Update last_login timestamp
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            $update_stmt->close();

            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found.";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
}
$conn->close();

$_SESSION['error'] = "Invalid request method.";
header("Location: login.php");
exit();
?>