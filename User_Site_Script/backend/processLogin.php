<?php
session_start();
include 'connection.php';

if (isset($_POST['btnLogin'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']); // Prevent injection
    $password = $_POST['password'];

    # Setup session to store user credentials temporary
    session_unset();
    $_SESSION['email'] = $email;

    $sql = "SELECT userID, email, username, password, status FROM userManagement
            WHERE email = ?"; // Prepared statement
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {            
            if ($row['status'] == 'Admin') {
                mysqli_stmt_close($stmt);
                mysqli_close($connection);

                header('Location: ../../Admin_Site_Script/frontend/login.php');
                exit();
            } elseif ($row['status'] == 'Active') { 
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['status'] = $row['status'];

                mysqli_stmt_close($stmt);
                mysqli_close($connection);

                $_SESSION['success'] = 'Login successful!';

                header('Location: ../frontend/dashboard.php');
                exit();
            } elseif ($row['status'] == 'Inactive') {
                mysqli_stmt_close($stmt);
                mysqli_close($connection);

                $_SESSION['error'] = 'Your account has been frozen by the admin!';
                header('Location: ../frontend/dashboard.php');
            }
            
        } else {
            $_SESSION['error'] = 'Login failed. Please check again your email and password!';
        }
    } else {
        $_SESSION['error'] = 'User not found! Please register an account';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}

header('Location: ../frontend/index.php'); // Back to main page
exit();
?>