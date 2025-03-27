<?php include '../backend/checkSession.php'; ?>

<?php
unset($_SESSION['rankings']);
unset($_SESSION['quizSession']);
?>

<?php
include '../backend/connection.php';

$sql = "WITH SongPerSession AS (
            SELECT quizSession, MIN(songID) AS songID
            FROM quizManagement
            GROUP BY quizSession
        )
        SELECT sm.artistID, am.picture, am.name, COUNT(*) AS artistCount
        FROM songManagement sm
        JOIN SongPerSession sps ON sm.songID = sps.songID
        JOIN artistManagement am ON sm.artistID = am.artistID
        GROUP BY sm.artistID, am.picture, am.name
        ORDER BY artistCount DESC
        LIMIT 3";

$result = mysqli_query($connection, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/selectArtist.css">

</head>

<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>
    <!-- Insert header.html -->
    <?php include 'header.html'; ?>

    
    <main>
        <div class="main-logo"></div>

        <form action="selectArtist.php" method="POST">
            <button class="play-button" name="btnPlayNow">Play Now</button>
        </form>

        <div class="activity-container">
            <div class="activity-header">
                <h3>Hit Quizzes</h3>
            </div>

            <div class="divider"></div>

            <div class="activity-boxes">
                <?php
                $counter = 0;

                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $artistID = htmlspecialchars($row['artistID']);
                        $artistName = htmlspecialchars($row['name']);
                        $artistPicture = htmlspecialchars($row['picture']);
                        ?>
                        <a href="#" class="artist-link" data-artist="<?php echo $artistName; ?>" data-artist-id="<?php echo $artistID; ?>">
                            <img src="<?php echo $artistPicture; ?>" alt="<?php echo $artistName; ?>" class="artist-picture">
                        </a>
                        <?php
                        $counter++;
                        if ($counter == 3) break;
                    endwhile;
                endif;

                while ($counter < 3):
                    ?>
                    <div class="activity-box">
                        Coming Soon!
                    </div>
                    <?php
                    $counter++;
                endwhile;
                ?>
            </div>
        </div>
    </main>
    
    <!-- Insert footer.html -->
    <?php include 'footer.html'; ?>
    
    <div id="difficulty-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closeModal">&times;</span>
            <h2 id="modal-title">Selected artist</h2>
            <hr class="title-underline">
            <h3>Select your difficulty level:</h3>
            <form action="../backend/processQuiz.php" method="POST">
                <div class="difficulty-buttons">
                    <!-- Store the selected artist in hidden input -->
                    <input type="hidden" name="artistID" id="selected-artist-input">

                    <button type="submit" class="beginner-button" name="difficulty" value="beginner">Beginner</button>
                    <button type="submit" class="expert-button" name="difficulty" value="expert">Expert</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let artistLinks = document.querySelectorAll(".artist-link");
            let modal = document.getElementById("difficulty-modal");
            let modalTitle = document.getElementById("modal-title");            
            let selectedArtistInput = document.getElementById("selected-artist-input");

            // Get the artist name based on what user clicked
            artistLinks.forEach(link => {
                link.addEventListener("click", function (event) {
                    event.preventDefault(); 
                    let selectedArtist = this.getAttribute("data-artist");
                    let selectedArtistID = this.getAttribute("data-artist-id");
                    modalTitle.textContent = `Selected artist: ${selectedArtist}`; 
                    selectedArtistInput.value = selectedArtistID;
                    modal.style.display = "flex"; 
                });
            });

            // If clicked outside the model, the modal will be closed
            window.addEventListener("click", function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });

            // Close modal when "X" is clicked
            document.getElementById('closeModal').addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });
    </script>

</body>
</html>

<?php
if (isset($connection)) {
    mysqli_close($connection);
}
?>
