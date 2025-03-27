<?php include '../backend/checkSession.php'; ?>

<?php
include '../backend/connection.php';

unset($_SESSION['rankings']);
unset($_SESSION['quizSession']); // If user clicked 'Retry button from leaderboard'

$sql = "SELECT artistID, name, picture FROM artistManagement";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/selectArtist.css">    

</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'header.html'; ?>

    <button class="back-button" onclick="history.back()">Back</button>

    <main>
        <!-- Artist Selection Section -->
        <section class="artist-selection">
            <h2>Select your desired artist</h2>
            <hr class="title-underline">          

            <div class="artist-list">
                <?php
                if (!empty($result) && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $artistID = htmlspecialchars($row['artistID']);
                        $artistName = htmlspecialchars($row['name']);
                        $artistPicture = htmlspecialchars($row['picture']);
                ?>
                        <a href="#" class="artist-link" data-artist="<?php echo $artistName; ?>" data-artist-id="<?php echo $artistID; ?>">
                            <img src="<?php echo $artistPicture; ?>" alt="<?php echo $artistName; ?>">
                            <?php echo $artistName ?>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No artists found.</p>
                <?php endif; ?>
            </div>            
            
        </section>
    </main>    
    
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
    
    <?php include 'footer.html'; ?>

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

