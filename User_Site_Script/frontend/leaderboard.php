<?php include '../backend/checkSession.php'; ?> 

<?php
include '../backend/connection.php';
$quizSession = $_SESSION['quizSession'];

$sql = "SELECT scores, totalTimeUsed
        FROM quizManagement
        WHERE quizSession = ?
        LIMIT 1";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die('Database error: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, 'i', $quizSession);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    unset($_SESSION['quizSession']);

    $_SESSION['error'] = 'An error occured :( Please try again.';
    header('Location: dashboard.php');
    exit();
}

$row = mysqli_fetch_assoc($result);

$totalScore = $row['scores'];
$totalTimeUsed = $row['totalTimeUsed'];

$rankings = $_SESSION['rankings'];
$rank1 = $rankings[0] ?? null;
$rank2 = $rankings[1] ?? null;
$rank3 = $rankings[2] ?? null;

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/leaderboard.css">

    
</head>
<body>
    <?php include 'header.html'; ?>
    
    <button class="back-button" onclick="window.location.replace('dashboard.php')">Quit Game</button>

    <audio id="background-audio" autoplay loop>
        <source src="sound_effect/quiz_bgm.mp3" type="audio/mp3">
    </audio>

    <main>
        <div class="leaderboard-header">
            <h1>Leaderboard</h1>
        </div>
        
        <section class="hero">
            <div class="player-container">
                <div class="square">
                    <?php if ($rank2): ?>
                        <h1><?php echo ($rank2['username'] == $_SESSION['username']) ? 'You' : $rank2['username']; ?></h1>
                        <p>Ranking: <?php echo $rank2['ranking']; ?></p>
                        <p>Total Score: <?php echo $rank2['totalScore']; ?></p>
                        <p>Total Time Taken: <?php echo round($rank2['totalTimeTaken'], 2); ?> seconds</p>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>
                </div>
                <div class="box box-medium"></div>
            </div>

            <div class="player-container">
                <div class="square">
                    <?php if ($rank1): ?>
                        <h1><?php echo ($rank1['username'] == $_SESSION['username']) ? 'You' : $rank1['username']; ?></h1>
                        <p>Ranking: <?php echo $rank1['ranking']; ?></p>
                        <p>Total Score: <?php echo $rank1['totalScore']; ?></p>
                        <p>Total Time Taken: <?php echo round($rank1['totalTimeTaken'], 2); ?> seconds</p>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>
                </div>
                <div class="box box-tall"></div>
            </div>
            
            <div class="player-container">
                <div class="square">
                    <?php if ($rank3): ?>
                        <h1><?php echo ($rank3['username'] == $_SESSION['username']) ? 'You' : $rank3['username']; ?></h1>
                        <p>Ranking: <?php echo $rank3['ranking']; ?></p>
                        <p>Total Score: <?php echo $rank3['totalScore']; ?></p>
                        <p>Total Time Taken: <?php echo round($rank3['totalTimeTaken'], 2); ?> seconds</p>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>
                </div>
                <div class="box box-short"></div>
            </div>
        </section>


        <div class="activity-container">
            <div class="divider"></div>
        </div>

        <br>
        <br>

        <div class="square">
            <h1>You</h1>
            <p>Total Score: <?php echo $totalScore; ?></p>
            <p>Total Time Taken: <?php echo $totalTimeUsed; ?> seconds</p>
            <p>Average Time: <?php echo $totalTimeUsed / 5; ?> seconds</p>
        </div>


        <div class="button-container">
            <button class="challenge-your-friend">Challenge Your Friend</button>
            <button class="exit" onclick="window.location.replace('dashboard.php')">Exit</button>
            <button class="retry" onclick="window.location.replace('selectArtist.php')">Retry</button>
        </div>

    </main>    
    <?php include 'footer.html'; ?>

    <script>
        // Set the volume of the audio element
        const audio = document.getElementById('background-audio');
        audio.volume = 0.5;
    </script>
</body>
</html>
