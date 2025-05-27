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
    $email = 'survivalperry@gmail.com'; // Updated for testing
    $reset_code = filter_var($_POST['reset_code'], FILTER_SANITIZE_STRING);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($reset_code) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: reset_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php");
        exit();
    }

    // Check the reset code
    $stmt = $conn->prepare("SELECT reset_code, reset_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Email not found.";
        error_log("Email not found for password reset: $email at " . date('Y-m-d H:i:s'));
        header("Location: reset_password.php");
        exit();
    }

    $user = $result->fetch_assoc();
    $stored_code = $user['reset_code'];
    $expiry = $user['reset_expiry'];
    $stmt->close();

    // Check if the code matches and hasn't expired
    $current_time = date('Y-m-d H:i:s');
    if ($stored_code !== $reset_code) {
        $_SESSION['error'] = "Invalid reset code.";
        error_log("Invalid reset code for $email at " . date('Y-m-d H:i:s'));
        header("Location: reset_password.php");
        exit();
    }

    if ($current_time > $expiry) {
        $_SESSION['error'] = "Reset code has expired.";
        error_log("Reset code expired for $email at " . date('Y-m-d H:i:s'));
        header("Location: reset_password.php");
        exit();
    }

    // Update the password (store as plain text as per previous request)
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Password reset successfully. Please log in with your new password.";
        error_log("Password reset successfully for $email at " . date('Y-m-d H:i:s'));
        header("Location: login.php");
    } else {
        $_SESSION['error'] = "Failed to reset password: " . $stmt->error;
        error_log("Failed to reset password: " . $stmt->error . " at " . date('Y-m-d H:i:s'));
        header("Location: reset_password.php");
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CleanPro Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .reset-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .reset-container h2 {
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #1e3a8a;
            border: none;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #1c355e;
        }
        .alert {
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <form action="reset_password.php" method="POST">
            <input type="text" name="reset_code" class="form-control" placeholder="Enter Reset Code" required>
            <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</body>
</html>