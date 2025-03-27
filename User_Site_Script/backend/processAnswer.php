<?php
session_start();

$start_time = $_SESSION['start_time'];

$time_taken = time() - $start_time;
$_SESSION['time_taken'] += $time_taken;

if (isset($_POST['selectedSong'], $_POST['correctAnswer'])) {
    $selectedSong = $_POST['selectedSong'];
    $correctAnswer = $_POST['correctAnswer'];
    $songID = $_POST['correctSongID'];

    
    if ($selectedSong === $correctAnswer) {
        $_SESSION['scores'] += 1;
    }
    
    $_SESSION['songID'][] = $songID;
    header('Location: ../frontend/revealAnswer.php');
    exit();
}
?>