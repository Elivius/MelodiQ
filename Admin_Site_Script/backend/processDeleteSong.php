<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_GET['selectedID'])) {
    $selectedID = explode(',', $_GET['selectedID']); // Convert string to array

    if (!empty($selectedID) && is_array($selectedID)) {
        // Sanitize to make sure selectedID is only integers
        $selectedID = array_map('intval', $selectedID);

        // Check if a foreign key referencing in other table
        $check_sql = "SELECT COUNT(*) FROM quizManagement WHERE songID IN (" . implode(',', array_fill(0, count($selectedID), '?')) . ")";
        if ($stmt_check = mysqli_prepare($connection, $check_sql)) {
            mysqli_stmt_bind_param($stmt_check, str_repeat('i', count($selectedID)), ...$selectedID);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_bind_result($stmt_check, $count);
            mysqli_stmt_fetch($stmt_check);
            mysqli_stmt_close($stmt_check);

            if ($count > 0) {
                $_SESSION['error'] = 'Cannot delete the song(s) because they are still referenced in quizManagement!';
                header('Location: ../frontend/songManagement.php');
                exit();
            } else {
                // Proceed with deletion
                $placeholders = implode(',', array_fill(0, count($selectedID), '?'));
                $sql = "DELETE FROM songManagement WHERE songID IN ($placeholders)";
                
                if ($stmt = mysqli_prepare($connection, $sql)) {
                    mysqli_stmt_bind_param($stmt, str_repeat('i', count($selectedID)), ...$selectedID);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['success'] = 'Selected song(s) deleted successfully.';
                    } else {
                        $_SESSION['error'] = 'Error deleting song(s): ' . mysqli_error($connection);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $_SESSION['error'] = 'Error preparing the delete query for song(s): ' . mysqli_error($connection);
                }
            }
        }
        
    } else {
        $_SESSION['error'] = 'Please select a row before performing this function';
    }
} else {
    $_SESSION['error'] = 'Please select a row before performing this function';
}

mysqli_close($connection);
header('Location: ../frontend/songManagement.php');
exit();
?>