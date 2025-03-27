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

        // Check if the user is 'Admin'
        $admin_sql = "SELECT COUNT(*) FROM userManagement WHERE userID IN (" . implode(',', array_fill(0, count($selectedID), '?')) . ") AND status = 'Admin'";

        if ($stmt_admin = mysqli_prepare($connection, $admin_sql)) {
            mysqli_stmt_bind_param($stmt_admin, str_repeat('i', count($selectedID)), ...$selectedID);
            mysqli_stmt_execute($stmt_admin);
            mysqli_stmt_bind_result($stmt_admin, $admin_count);
            mysqli_stmt_fetch($stmt_admin);
            mysqli_stmt_close($stmt_admin);
        }

        // First Query - Check if userID has been referenced in quizManagement
        $quiz_sql = "SELECT COUNT(*) FROM quizManagement WHERE userID IN (" . implode(',', array_fill(0, count($selectedID), '?')) . ")";

        if ($stmt_quiz = mysqli_prepare($connection, $quiz_sql)) {
            mysqli_stmt_bind_param($stmt_quiz, str_repeat('i', count($selectedID)), ...$selectedID);
            mysqli_stmt_execute($stmt_quiz);
            mysqli_stmt_bind_result($stmt_quiz, $quiz_count);
            mysqli_stmt_fetch($stmt_quiz);
            mysqli_stmt_close($stmt_quiz);
        }

        // Second Query - Check if userID has been referenced in feedbackManagement
        $feedback_sql = "SELECT COUNT(*) FROM feedbackManagement WHERE userID IN (" . implode(',', array_fill(0, count($selectedID), '?')) . ")";

        if ($stmt_feedback = mysqli_prepare($connection, $feedback_sql)) {
            mysqli_stmt_bind_param($stmt_feedback, str_repeat('i', count($selectedID)), ...$selectedID);
            mysqli_stmt_execute($stmt_feedback);
            mysqli_stmt_bind_result($stmt_feedback, $feedback_count);
            mysqli_stmt_fetch($stmt_feedback);
            mysqli_stmt_close($stmt_feedback);
        }

        if ($quiz_count > 0 || $feedback_count > 0) {
            $_SESSION['error'] = 'Cannot delete the user(s) because they are still referenced in either quizManagement or feedbackManagement!';
            header('Location: ../frontend/userManagement.php');
            exit();
        } elseif ($admin_count > 0) {
            $_SESSION['error'] = 'Deleting admin details are not allowed!';
            header('Location: ../frontend/userManagement.php');
            exit();
        } else {
            // Proceed with deletion
            $placeholders = implode(',', array_fill(0, count($selectedID), '?'));
            $sql = "DELETE FROM userManagement WHERE userID IN ($placeholders)";
            
            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, str_repeat('i', count($selectedID)), ...$selectedID);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['success'] = 'Selected user(s) deleted successfully.';
                } else {
                    $_SESSION['error'] = 'Error deleting user(s): ' . mysqli_error($connection);
                }
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['error'] = 'Error preparing the delete query for user(s): ' . mysqli_error($connection);
            }
        }
        
        
    } else {
        $_SESSION['error'] = 'Please select a row before performing this function';
    }
} else {
    $_SESSION['error'] = 'Please select a row before performing this function';
}

mysqli_close($connection);
header('Location: ../frontend/userManagement.php');
exit();
?>