<?php include '../backend/checkSession.php';?>

<?php
include '../backend/connection.php';

$songID = $_GET['selectedID'];

$sql = "SELECT songName, artistID, version, mp3Link, spotifyLink,
        youtubeMusicLink, songCoverPicture FROM songManagement
        WHERE songID = ?";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $songID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $songName, $artistID, $version, $mp3Link, $spotifyLink, $youtubeMusicLink, $songCoverPicture);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    $_SESSION['error'] = 'An error occured. Please try again.';
    header('Location: ../frontend/songManagement.php');
    exit();
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles4.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>

    <?php include 'header.html'; ?>

    <main class="content">
        <div class="modal">
            <div class="modal-header">
                <h2>Edit Song</h2>
                <a href="songManagement.php" class="close-btn" aria-label="Close Modal">&times;</a>
            </div>
            <form class="modal-body" action="../backend/processEditSong.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="versionInput" name="version" value="Regular">
                <input type="hidden" name="songID" value="<?php echo $songID; ?>">

                <div class="form-row">

                    <div class="form-group">
                        <label>Song Name</label>
                        <input type="text" id="songName" name="songName" placeholder="Enter Song Name" value="<?php echo htmlspecialchars($songName); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Artist ID</label>
                        <input type="number" id="artistID" name="artistID" placeholder="Enter ArtistID" value="<?php echo htmlspecialchars($artistID); ?>" required>
                    </div>

                </div>
                
                <div class="form-row">

                    <div class="form-group">
                        <label>MP3 Link</label>
                        <input type="text" id="mp3Link" name="mp3Link" placeholder="Enter MP3 Link" value="<?php echo htmlspecialchars($mp3Link); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Spotify Link</label>
                        <input type="text" id="spotify" name="spotify" placeholder="Enter Spotify Link" value="<?php echo htmlspecialchars($spotifyLink); ?>" required>
                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label>Youtube Music Link</label>
                        <input type="text" id="youtubeMusic" name="youtubeMusic" placeholder="Enter Youtube Music Link" value="<?php echo htmlspecialchars($youtubeMusicLink); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Song Cover Picture Link</label>
                        <input type="text" id="songCoverPictureLink" name="songCoverPictureLink" placeholder="Enter Song Cover Picture Link" value="<?php echo htmlspecialchars($songCoverPicture); ?>" required>
                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group version">
                        <button type="button" class="version-btn <?php echo ($version === 'Regular') ? 'active' : ''; ?>" 
                            onclick="setVersion(this, 'Regular')">Regular</button>
                            
                        <button type="button" class="version-btn <?php echo ($version === 'Karaoke') ? 'active' : ''; ?>" 
                            onclick="setVersion(this, 'Karaoke')">Karaoke</button>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn" name="save-btn">Confirm Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        © 2024 MelodiQ Limited. All Rights Reserved
    </footer>
    <script src="script2.js"></script>
</body>
</html>
