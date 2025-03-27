<?php
session_start();
include 'connection.php';

if (isset($_POST['btnLogin-admin'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']); // Prevent injection
    $password = $_POST['password'];

    $sql = "SELECT userID, email, username, password, status FROM userManagement
            WHERE email = ? AND status = 'Admin'"; // Prepared statement

    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['status'] = $row['status'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            
            header('Location: ../frontend/userManagement.php');
            exit();
        } else {
            $_SESSION['error'] = 'Login failed. Please check again your email and password!';
        }
    } else {
        $_SESSION['error'] = 'Only admin can access!';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}

header('Location: ../frontend/login.php'); // Back to main page
exit();
?>