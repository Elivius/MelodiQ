<?php include '../backend/checkSession.php'; ?> 

<?php
include '../backend/connection.php';

if (!isset($_SESSION['quizSession'])) {
    $_SESSION['error'] = 'Please select and an artist and the level of difficulty!';
    header('Location: ../frontend/selectArtist.php');
    exit();
}

if ($_SESSION['quiz_attempt'] > 5) {
    unset($_SESSION['quiz_attempt']);
    unset($_SESSION['start_time']);
    unset($_SESSION['end_time']);
    header('Location: ../backend/processResult.php');
    exit();
}

$_SESSION['start_time'] = time();
$artistID = $_SESSION['artistID'];
$difficulty = $_SESSION['difficulty'];
$attempt = $_SESSION['quiz_attempt'];

if ($difficulty == 'beginner') {
    $difficultySQL = "AND version = 'Regular'";
} else if ($difficulty == 'expert') {
    $difficultySQL = "AND version = 'Karaoke'";
}


function fetchSongs($connection, $artistID, $difficultySQL) {
    $sql = "SELECT songID, songName, mp3Link, spotifyLink, youtubeMusicLink, songCoverPicture
            FROM songManagement
            WHERE artistID = ?
            $difficultySQL
            ORDER BY RAND() LIMIT 4";
    
    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die('Database error: ' . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 's', $artistID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    $songs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $songs[] = $row;
    }

    return $songs;
}

// Initial fetch
$songs = fetchSongs($connection, $artistID, $difficultySQL);

$previousSongIDs = $_SESSION['songID'];

// Loop until we get at least one new song
do {
    // Filter out already answered songs
    $availableSongs = array_filter($songs, function ($song) use ($previousSongIDs) {
        return !in_array($song['songID'], $previousSongIDs);
    });

    // If no new songs, fetch again
    if (empty($availableSongs)) {
        $songs = fetchSongs($connection, $artistID, $difficultySQL);
    }

} while (empty($availableSongs));

$answerSongIndex = array_rand($availableSongs);
$selectedSong = $availableSongs[$answerSongIndex];

$correctAnswer = htmlspecialchars($selectedSong['songName']);
$songMP3 = htmlspecialchars($selectedSong['mp3Link']);
$songID = htmlspecialchars($selectedSong['songID']);

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
    <link rel="stylesheet" href="css/quiz.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'header.html'; ?>

    <!-- Audio element to play on page load -->
    <audio id="songAudio" src="<?php echo $songMP3; ?>" autoplay preload="auto"></audio>

    <!-- Quit Game Button -->
    <button class="back-button">Quit Game</button>
    
    <main>
        <div class="box box-large">
            <!-- Progress Dots -->
            <div class="progress-dots">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="dot <?php echo ($attempt >= $i) ? 'active' : ''; ?>"></div>
                <?php endfor; ?>
            </div>

            <div class="timer">0:00</div>

            <!-- GIFs -->
            <div class="gif-container">
                <img src="images/musicWave.gif" alt="Music Wave GIF">
            </div>
            
            <div class="playBtn-container">
                <button id="btnPlayMusic" style="background: none; border: none; color: white; font-size: 26px; cursor: pointer;"><i class="fa-solid fa-pause"></i></button>
            </div>

            <!-- Song buttons at the bottom of the box -->
            <form id="answer-form" action="../backend/processAnswer.php" method="POST">
                <div class="answer-container">
                    <?php
                    foreach ($songs as $song) {
                        $songName = htmlspecialchars($song['songName']);
                        $songMP3 = htmlspecialchars($song['mp3Link']);
                    ?>
                        <button type="submit" class="song-button" name="selectedSong" value="<?php echo $songName; ?>">
                            <?php echo $songName; ?>
                        </button>
                    <?php } ?>

                    <!-- Make a hidden form to send the answer to checkAnswer.php -->
                    <input type="hidden" name="correctAnswer" value="<?php echo $correctAnswer; ?>"> 
                    <input type="hidden" name="correctSongID" value="<?php echo $songID; ?>">                    
                </div>
            </form>

        </div>
    </main>
    
    
    <!-- Modal for Quit Game Confirmation -->
    <div id="quit-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closeModal">&times;</span>
            <p>Are you sure you want to quit the game? Your progess won't be saved</p>
            <div class="modal-buttons">
                <button id="leaveBtn" class="leaveBtn">Leave the Game</button>
                <button id="stayBtn" class="stayBtn">Stay in the Game</button>
            </div>
        </div>
    </div>
    
    <?php include 'footer.html'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Display the play and pause icon
            let playMusicBtn = document.getElementById("btnPlayMusic");
            let audio = document.getElementById("songAudio");
            let gif = document.querySelector(".gif-container img");

            let playIcon = document.createElement('i');
            playIcon.className = 'fa-solid fa-play';

            let pauseIcon = document.createElement('i');
            pauseIcon.className = 'fa-solid fa-pause';

            playMusicBtn.addEventListener("click", function () {
                this.textContent = "";
                
                if (audio.paused) {
                    audio.play();
                    this.appendChild(pauseIcon);
                    gif.src = "images/musicWave.gif";
                } else {
                    audio.pause();
                    this.appendChild(playIcon);
                    gif.src = "images/staticMusicWave.gif"
                }
            });

            // Get the Quit Confirmation Modal
            let quitGameBtn = document.querySelector(".back-button");
            let quitModal = document.getElementById("quit-modal");
            let stayBtn = document.getElementById("stayBtn");
            let leaveBtn = document.getElementById("leaveBtn");
            
            // Show the modal
            quitGameBtn.onclick = function () {
                quitModal.style.display = "flex";
            };

            stayBtn.onclick = function() {
                quitModal.style.display = "none";
            };

            let isLeaving = false;

            leaveBtn.onclick = function() {                
                isLeaving = true;
                navigator.sendBeacon("../backend/processQuitGame.php");
                window.location.replace("dashboard.php");
            };

            // If clicked outside the model, the modal will be closed
            window.addEventListener("click", function (event) {
                if (event.target == quitModal) {
                    quitModal.style.display = "none";
                }
            });

            // Close modal when "X" is clicked
            document.getElementById('closeModal').addEventListener('click', function () {
                quitModal.style.display = 'none';
            });


            // Song button click handling
            let songBtns = document.querySelectorAll(".song-button");
            let form = document.getElementById("answer-form");
            let hiddenInput = document.createElement("input");

            for (let i = 0; i < songBtns.length; i++) {
                songBtns[i].addEventListener("click", function(event) {
                    event.preventDefault();
                    
                    isLeaving = true;

                    let selectedSong = this.value;
                    let correctAnswer = document.querySelector("input[name='correctAnswer']").value;

                    for (let j = 0; j < songBtns.length; j++) {
                        songBtns[j].disabled = true; 
                        songBtns[j].classList.remove("correct-answer", "wrong-answer");
                    }

                    if (selectedSong === correctAnswer) {
                        this.classList.add("correct-answer");
                    } else {
                        this.classList.add("wrong-answer");

                        for (let k = 0; k < songBtns.length; k++) {
                            if (songBtns[k].value === correctAnswer) {
                                songBtns[k].classList.add("correct-answer");
                            }
                        }
                    }

                    // Create another <Input>
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "selectedSong";
                    hiddenInput.value = this.value;
                    form.appendChild(hiddenInput);                    

                    setTimeout(function () {
                        form.submit();
                    }, 1500);                    
                });
            }

            window.addEventListener("beforeunload", function() {
                if (!isLeaving) {
                    navigator.sendBeacon("../backend/processQuitGame.php");
                }
            });

            let timerDisplay = document.querySelector('.timer');
            let time = <?php echo $_SESSION['time_taken']; ?>;

            function updateTimer() {
                let minutes = Math.floor(time / 60);
                let seconds = time % 60;
                
                // Add leading zero if seconds are less than 10
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.textContent = `${minutes}:${seconds}`;
                
                time++;
            }

            let timerInterval = setInterval(updateTimer, 1000);
        });
    </script>
</body>
</html>
