<?php
session_start();
include 'connection.php';


if (isset($_POST['btnSubmit'])) {
    $feedback = mysqli_real_escape_string($connection, $_POST['feedback']);

    if (!empty($feedback)) {
        $userID = $_SESSION['userID'];

        $sql = "INSERT INTO feedbackManagement (userID, message) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $sql);

        if (!$stmt) {
            die('Database error: ' . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt, 'is', $userID, $feedback);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = 'Thank you for your feedback! We truly appreciate your support and insights.';
        } else {
            $_SESSION['error'] = 'Something went wrong. Please try again.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = 'Feedback cannot be empty!';
    }

    mysqli_close($connection);
}

header('Location: ../frontend/leaveFeedback.php');
exit();
?>