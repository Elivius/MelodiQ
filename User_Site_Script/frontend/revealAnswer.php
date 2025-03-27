<?php include '../backend/checkSession.php'; ?> 

<?php
include '../backend/connection.php';

if (isset($_SESSION['songID'])) {
    $songID = end($_SESSION['songID']);
    $attempt = $_SESSION['quiz_attempt'];
    $_SESSION['quiz_attempt'] += 1;
} else {
    $_SESSION['error'] = 'Please select and an artist and the level of difficulty!';
    header('Location: selectArtist.php');
    exit();
}

$sql = "SELECT s.songName, s.mp3Link, s.spotifyLink, s.youtubeMusicLink, s.songCoverPicture, a.name
        FROM songManagement AS s
        JOIN artistManagement AS a ON s.artistID = a.artistID
        WHERE s.songID = ?";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die('Database error: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, 's', $songID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);
mysqli_close($connection);

$row = mysqli_fetch_assoc($result);

$artist = $row['name'];
$songName = $row['songName'];
$songMP3 = $row['mp3Link'];
$spotifyLink = $row['spotifyLink'];
$youtubeMusicLink = $row['youtubeMusicLink'];
$songCoverPicture = $row['songCoverPicture'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/revealAnswer.css">
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
        <section class="hero">
            <div class="image-container">
                
                <!-- Progress Dots -->
                <div class="progress-dots">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="dot <?php echo ($attempt >= $i) ? 'active' : ''; ?>"></div>
                    <?php endfor; ?>
                </div>

                <img src="<?php echo $songCoverPicture; ?>" alt="Music Cover Image" class="hero-image">
                <div class="playBtn-container">
                    <button id="btnPlayMusic" style="background: none; border: none; color: white; font-size: 66px; cursor: pointer;"><i class="fa-solid fa-pause"></i></button>
                </div>
            </div>            

            <div class="song-details">
                <h3><?php echo $songName; ?></h3>
                <p><?php echo $artist; ?></p>

                <div class="button-container">
                    <a href="<?php echo $spotifyLink; ?>" target="_blank">
                        <button class="spotify-button">
                            <img src="images/listenOnSpotify.png" alt="Listen on Spotify button">
                        </button>
                    </a>

                    <a href="<?php echo $youtubeMusicLink; ?>" target="_blank">
                        <button class="youtube-button">
                            <img src="images/listenOnYoutubeMusic.png" alt="Listen on Youtube Music button">                    
                        </button>
                    </a>
                </div>
                
                <form action="quiz.php" method="POST">
                    <button class="continue-button" id="continueBtn">Continue</button>
                </form>
            </div>
            

        </section>
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
                } else {
                    audio.pause();
                    this.appendChild(playIcon);
                }
            });

            // Get the Quit Confirmation Modal
            let quitGameBtn = document.querySelector(".back-button");
            let quitModal = document.getElementById("quit-modal");
            let stayBtn = document.getElementById("stayBtn");
            let leaveBtn = document.getElementById("leaveBtn");
            let continueBtn = document.getElementById("continueBtn");
            
            // Show the modal
            quitGameBtn.onclick = function () {
                quitModal.style.display = "flex";
            };

            stayBtn.onclick = function() {
                quitModal.style.display = "none";
            };

            let isLeaving = false;

            continueBtn.addEventListener("click", function () {
                isLeaving = true;
            });

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

            window.addEventListener("beforeunload", function() {                
                if (!isLeaving) {
                    navigator.sendBeacon("../backend/processQuitGame.php");
                }
            });
        });
    </script>
</body>
</html>
