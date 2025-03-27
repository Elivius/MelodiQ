<?php
include 'connection.php';
session_start();

$quizSession = $_SESSION['quizSession'];
$songIDs = $_SESSION['songID'];
$userID = $_SESSION['userID'];
$scores = $_SESSION['scores'];
$difficulty = $_SESSION['difficulty'];
$time_taken = $_SESSION['time_taken'];

$sql = "INSERT INTO quizManagement (quizSession, songID, userID, scores, difficulty, totalTimeUsed) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $sql);

foreach ($songIDs as $songID) {
    mysqli_stmt_bind_param($stmt, "iiiisi", $quizSession, $songID, $userID, $scores, $difficulty, $time_taken);
    mysqli_stmt_execute($stmt);
}

if (!$stmt) {
    die('Database error: ' . mysqli_error($connection));
}

if (mysqli_stmt_affected_rows($stmt) <= 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    unset($_SESSION['artistID']);
    unset($_SESSION['difficulty']);
    unset($_SESSION['quizSession']);
    unset($_SESSION['scores']);
    unset($_SESSION['songID']);
    unset($_SESSION['time_taken']);

    $_SESSION['error'] = 'An error occured :( Please try again.';
    header('Location: ../frontend/dashboard.php');
    exit();
}

unset($_SESSION['scores']);
unset($_SESSION['songID']);
unset($_SESSION['time_taken']);

mysqli_stmt_close($stmt);
mysqli_close($connection);
header('Location: processLeaderboard.php');
exit();
?>