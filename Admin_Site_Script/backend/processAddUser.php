<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_POST['submit-btn']) && $_POST['email'] && $_POST['username'] && $_POST['password'] && $_POST['passwordConfirmation'] && $_POST['status']) {
    
    $email = mysqli_real_escape_string($connection, strtolower($_POST['email']));
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['passwordConfirmation'];
    $status = $_POST['status'];

    // Password validation
    if (strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)) {
        $_SESSION['error'] = 'Password must be at least 8 characters long, including:
        <br>- An uppercase letter<br>- A lowercase letter<br>- A number<br>- A special character!';
        header('Location: ../frontend/add_user.php');
        exit();
    }

    if ($password != $passwordConfirmation) {
        $_SESSION['error'] = 'Confirmation password must same as password!';
        header('Location: ../frontend/add_user.php');
        exit();
    }

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email exist
    $sql_CheckEmail = "SELECT email FROM userManagement WHERE email = ?";
    $stmtEmail = mysqli_prepare($connection, $sql_CheckEmail);

    if (!$stmtEmail) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtEmail, 's', $email);
    mysqli_stmt_execute($stmtEmail);
    $resultEmail = mysqli_stmt_get_result($stmtEmail);
    $emailExist = mysqli_fetch_assoc($resultEmail) ? true : false;
    mysqli_stmt_close($stmtEmail);

    // Check if username exist
    $sql_CheckUsername = "SELECT username FROM userManagement WHERE username = ?";
    $stmtUsername = mysqli_prepare($connection, $sql_CheckUsername);

    if (!$stmtUsername) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtUsername, 's', $username);
    mysqli_stmt_execute($stmtUsername);
    $resultUsername = mysqli_stmt_get_result($stmtUsername);
    $usernameExist = mysqli_fetch_assoc($resultUsername) ? true : false;
    mysqli_stmt_close($stmtUsername);

    // Return error message
    if ($emailExist && $usernameExist) {
        $_SESSION['error'] = 'Email and Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/add_user.php');
        exit();
    } elseif ($emailExist) {
        $_SESSION['error'] = 'Email already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/add_user.php');
        exit();
    } elseif ($usernameExist) {
        $_SESSION['error'] = 'Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/add_user.php');
        exit();
    }

    // Insert data if both not exist and valid
    $sql_Insert = "INSERT INTO userManagement (email, username, password, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql_Insert);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $email, $username, $password, $status);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['success'] = 'Successfully added user!';
        header('Location: ../frontend/userManagement.php');
        exit();       
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['error'] = 'Error adding user!';
        header('Location: ../frontend/userManagement.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/add_user.php');
    exit();
}
?>