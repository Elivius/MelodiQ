<?php
session_start();
include 'connection.php';

if (isset($_POST['difficulty']) && isset($_POST['artistID'])) {
    $_SESSION['difficulty'] = $_POST['difficulty'];
    $_SESSION['artistID'] = $_POST['artistID'];
    $_SESSION['quizSession'] = time() + $_SESSION['userID']; // Prevent more than one user start at the same time, so I add userID
    $_SESSION['quiz_attempt'] = 1;
    $_SESSION['scores'] = 0;
    $_SESSION['songID'] = [];
    $_SESSION['time_taken'] = 0;
}

if (isset($_SESSION['artistID']) && isset($_SESSION['difficulty']) && isset($_SESSION['quiz_attempt']) && isset($_SESSION['scores'])) {
    $artistID = $_SESSION['artistID'];
    $difficulty = $_SESSION['difficulty'];

    if ($difficulty == 'beginner') {
        $difficultySQL = "AND version = 'Regular'";
    } else if ($difficulty == 'expert') {
        $difficultySQL = "AND version = 'Karaoke'";
    }
} else {
    $_SESSION['error'] = 'Please select and an artist and the level of difficulty!';
    header('Location: ../frontend/selectArtist.php');
    exit();
}

$countSql = "SELECT COUNT(*) AS totalSongs 
             FROM songManagement 
             WHERE artistID = ? 
             $difficultySQL";

$countStmt = mysqli_prepare($connection, $countSql);

if (!$countStmt) {
    die('Database error: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($countStmt, 's', $artistID);
mysqli_stmt_execute($countStmt);
$countResult = mysqli_stmt_get_result($countStmt);
$row = mysqli_fetch_assoc($countResult);
$totalSongs = $row['totalSongs'];

if ($totalSongs < 5) {
    $_SESSION['error'] = "This quiz will be coming soon!";
    unset($_SESSION['artistID']);
    unset($_SESSION['difficulty']);
    unset($_SESSION['quizSession']);
    unset($_SESSION['quiz_attempt']);
    unset($_SESSION['scores']);
    unset($_SESSION['songID']);
    unset($_SESSION['time_taken']);

    mysqli_stmt_close($countStmt);
    mysqli_close($connection);

    header('Location: ../frontend/selectArtist.php');
    exit();
} else {

    mysqli_stmt_close($countStmt);
    mysqli_close($connection);

    header('Location: ../frontend/countdown.php');
    exit();
}
?>