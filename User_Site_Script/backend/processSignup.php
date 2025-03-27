<?php
session_start();
include 'connection.php';

if (isset($_POST['btnSignUp'])) {
    $email = mysqli_real_escape_string($connection, strtolower($_POST['email']));
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['passwordConfirmation'];

    # Setup session to store user credentials temporary
    session_unset();
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;

    // Password validation
    if (strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)) {
        $_SESSION['error'] = 'Password must be at least 8 characters long, including:
        <br>- An uppercase letter<br>- A lowercase letter<br>- A number<br>- A special character!';
        header('Location: ../frontend/index.php');
        exit();
    }

    if ($password != $passwordConfirmation) {
        $_SESSION['error'] = 'Confirmation password must same as password!';
        header('Location: ../frontend/index.php');
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
        header('Location: ../frontend/index.php');
        exit();
    } elseif ($emailExist) {
        $_SESSION['error'] = 'Email already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/index.php');
        exit();
    } elseif ($usernameExist) {
        $_SESSION['error'] = 'Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/index.php');
        exit();
    }

    // Insert data if both not exist and valid
    $sql_Insert = "INSERT INTO userManagement (email, username, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql_Insert);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 'sss', $email, $username, $password);

    if (mysqli_stmt_execute($stmt)) {
        $userID = mysqli_insert_id($connection);

        $_SESSION['userID'] = $userID;
        $_SESSION['username'] = $username;
        $_SESSION['status'] = 'Active';

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['success'] = 'Account successfully registered!';

        header('Location: ../frontend/dashboard.php');
        exit();        
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        header('Location: ../frontend/index.php');
        exit();
    }
}

?>