<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_POST['save-btn']) && $_POST['name'] && $_POST['pictureLink'] && $_POST['artistID']) {
    
    $artistID = $_POST['artistID'];
    $artistName = trim($_POST['name']);
    $pictureLink = trim($_POST['pictureLink']);
     
    // Check if is valid URL
    $encodedLink = str_replace(' ', '%20', $pictureLink); // Encode spaces

    if (!filter_var($encodedLink, FILTER_VALIDATE_URL)) { 
        $_SESSION['error'] = 'Invalid artist picture link!';
        header('Location: ../frontend/add_artist.php');
        exit();
    }

    $sql_UpdateArtist = "UPDATE artistManagement SET name = ?, picture = ? WHERE artistID = ?";
    $stmt = mysqli_prepare($connection, $sql_UpdateArtist);

    if (!$stmt) {
        die("Database error: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "ssi", $artistName, $encodedLink, $artistID);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['success'] = 'Artist successfully edited!';
        header('Location: ../frontend/artistManagement.php');
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        $_SESSION['error'] = 'Error editing artist!';
        header('Location: ../frontend/artistManagement.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/edit_artist.php');
    exit();
}
?>