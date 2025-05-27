<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cleanpro_db');
if ($conn->connect_error) {
    $_SESSION['error'] = "Database connection failed: " . $conn->connect_error;
    error_log("Database connection failed: " . $conn->connect_error . " at " . date('Y-m-d H:i:s'));
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stored_password = $user['password'];

        // Check if the stored password is hashed or plain text
        if (password_verify($password, $stored_password)) {
            // Password is hashed
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin.php");
        } elseif ($password === $stored_password) {
            // Password is plain text
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin.php");
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            error_log("Invalid login attempt for username: $username at " . date('Y-m-d H:i:s'));
            header("Location: login.php");
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        error_log("Invalid login attempt for username: $username at " . date('Y-m-d H:i:s'));
        header("Location: login.php");
    }

    $stmt->close();
    $conn->close();
    exit();
}

$_SESSION['error'] = "Invalid request method.";
error_log("Invalid request method in authenticate.php at " . date('Y-m-d H:i:s'));
header("Location: login.php");
exit();
?>