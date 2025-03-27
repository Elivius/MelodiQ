<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_POST['submit-btn']) && $_POST['name'] && $_POST['pictureLink']) {
    
    $artistName = trim($_POST['name']);
    $pictureLink = trim($_POST['pictureLink']);
     
    // Check if is valid URL
    $encodedLink = str_replace(' ', '%20', $pictureLink); // Encode spaces

    if (!filter_var($encodedLink, FILTER_VALIDATE_URL)) { 
        $_SESSION['error'] = 'Invalid artist picture link!';
        header('Location: ../frontend/add_artist.php');
        exit();
    }

    $sql_InsertArtist = "INSERT INTO artistManagement (name, picture) VALUES (?, ?)";
    $stmtArtist = mysqli_prepare($connection, $sql_InsertArtist);

    if (!$stmtArtist) {
        die("Database error: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtArtist, "ss", $artistName, $encodedLink);

    // Execute the statement
    if (mysqli_stmt_execute($stmtArtist)) {
        mysqli_stmt_close($stmtArtist);
        mysqli_close($connection);

        $_SESSION['success'] = 'Successfully added artist!';
        header('Location: ../frontend/artistManagement.php');
        exit();
    } else {
        mysqli_stmt_close($stmtArtist);
        mysqli_close($connection);

        $_SESSION['error'] = 'Error adding artist!';
        header('Location: ../frontend/artistManagement.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/add_artist.php');
    exit();
}
?>