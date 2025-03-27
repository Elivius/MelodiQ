<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_POST['save-btn']) && $_POST['userID'] && $_POST['email'] && $_POST['username'] && $_POST['status']) {
    
    $userID = $_POST['userID'];
    $email = mysqli_real_escape_string($connection, strtolower($_POST['email']));
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $status = $_POST['status'];

    // Check if email exist
    $sql_CheckEmail = "SELECT email FROM userManagement WHERE email = ? AND userID != ?";
    $stmtEmail = mysqli_prepare($connection, $sql_CheckEmail);

    if (!$stmtEmail) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtEmail, 'si', $email, $userID);
    mysqli_stmt_execute($stmtEmail);
    $resultEmail = mysqli_stmt_get_result($stmtEmail);
    $emailExist = mysqli_fetch_assoc($resultEmail) ? true : false;
    mysqli_stmt_close($stmtEmail);

    // Check if username exist
    $sql_CheckUsername = "SELECT username FROM userManagement WHERE username = ? AND userID != ?";
    $stmtUsername = mysqli_prepare($connection, $sql_CheckUsername);

    if (!$stmtUsername) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtUsername, 'si', $username, $userID);
    mysqli_stmt_execute($stmtUsername);
    $resultUsername = mysqli_stmt_get_result($stmtUsername);
    $usernameExist = mysqli_fetch_assoc($resultUsername) ? true : false;
    mysqli_stmt_close($stmtUsername);

    // Return error message
    if ($emailExist && $usernameExist) {
        $_SESSION['error'] = 'Email and Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/edit_user.php?selectedID=' . urlencode($userID));
        exit();
    } elseif ($emailExist) {
        $_SESSION['error'] = 'Email already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/edit_user.php?selectedID=' . urlencode($userID));
        exit();
    } elseif ($usernameExist) {
        $_SESSION['error'] = 'Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/edit_user.php?selectedID=' . urlencode($userID));
        exit();
    }

    // Insert data if both not exist and valid
    $sql_UpdateUser = "UPDATE userManagement SET email = ?, username = ?, status = ? 
                        WHERE userID = ?";
    $stmt = mysqli_prepare($connection, $sql_UpdateUser);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 'sssi', $email, $username, $status, $userID);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['success'] = 'User successfully edited!';
        header('Location: ../frontend/userManagement.php');
        exit();       
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['error'] = 'Error editing user!';
        header('Location: ../frontend/userManagement.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/edit_user.php');
    exit();
}
?>