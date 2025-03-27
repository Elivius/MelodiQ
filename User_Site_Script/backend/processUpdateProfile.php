<?php
session_start();
include 'connection.php';


if (isset($_POST['btnUpdate'])) {
    $userID = $_SESSION['userID'];
    $email = mysqli_real_escape_string($connection, strtolower($_POST['email']));
    $username = mysqli_real_escape_string($connection, $_POST['username']);

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
    $sql_CheckUsername = "SELECT username FROM userManagement 
                            WHERE username = ? AND userID != ?";

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
        header('Location: ../frontend/profile.php');
        exit();
    } elseif ($emailExist) {
        $_SESSION['error'] = 'Email already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/profile.php');
        exit();
    } elseif ($usernameExist) {
        $_SESSION['error'] = 'Username already exists!';
        mysqli_close($connection);
        header('Location: ../frontend/profile.php');
        exit();
    }

    // Insert data if both not exist and valid
    $sql_Insert = "UPDATE userManagement SET email = ?, username = ?
                    WHERE userID = ?";

    $stmt = mysqli_prepare($connection, $sql_Insert);

    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 'ssi', $email, $username, $userID);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = 'Details updated successful!';

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        header('Location: ../frontend/profile.php');
        exit();        
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        header('Location: ../frontend/profile.php');
        exit();
    }
}
?>