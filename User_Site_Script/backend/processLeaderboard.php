<?php
include 'connection.php';

session_start();

$difficulty = $_SESSION['difficulty'];
$artistID = $_SESSION['artistID'];

$sql = "WITH RankedResults AS (
            SELECT q.difficulty, s.artistID, u.username, q.scores AS totalScore,
            SUM(q.totalTimeUsed) / 5 AS totalTimeTaken,

            RANK() OVER (
                PARTITION BY s.artistID, q.difficulty
                ORDER BY q.scores DESC, SUM(q.totalTimeUsed) ASC
            ) AS ranking
        FROM 
            quizManagement q
        JOIN 
            songManagement s ON s.songID = q.songID
        JOIN 
            userManagement u ON u.userID = q.userID
        GROUP BY 
            q.quizSession, s.artistID, q.difficulty, q.userID, u.username, q.scores
        )
                
        SELECT username, totalScore, totalTimeTaken, ranking FROM RankedResults
        WHERE ranking <= 3 AND difficulty = ? AND artistID = ?
        ORDER BY ranking";

// Execute the query
$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die('Database error: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, 'si', $difficulty, $artistID);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $rankings = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rankings[] = $row;

    }

    $_SESSION['rankings'] = $rankings;
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    unset($_SESSION['artistID']);
    unset($_SESSION['difficulty']);
    
    $_SESSION['error'] = 'An error occured :( Please try again.';
    header('Location: ../frontend/dashboard.php');
    exit();
}

unset($_SESSION['artistID']);
unset($_SESSION['difficulty']);
mysqli_stmt_close($stmt);
mysqli_close($connection);
header('Location: ../frontend/leaderboard.php');
exit();
?>