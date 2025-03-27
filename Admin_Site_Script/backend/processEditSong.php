<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}
if (isset($_POST['save-btn']) && 
    isset($_POST['songName']) && 
    isset($_POST['artistID']) && 
    isset($_POST['version']) && 
    isset($_POST['mp3Link']) && 
    isset($_POST['spotify']) && 
    isset($_POST['youtubeMusic']) && 
    isset($_POST['songCoverPictureLink'])) {
        $songID = $_POST['songID'];
        $songName = trim($_POST['songName']);
        $artistID = trim($_POST['artistID']);
        $version = trim($_POST['version']);
        $mp3Link = trim($_POST['mp3Link']);
        $spotifyLink = trim($_POST['spotify']);
        $youtubeMusicLink = trim($_POST['youtubeMusic']);
        $songCoverPictureLink = trim($_POST['songCoverPictureLink']);
     
        // Check if is valid URL
        $links = [$mp3Link, $spotify, $youtubeMusic, $songCoverPictureLink];
        
        foreach ($links as &$link) { // Use reference to update original values
            if (!empty($link)) { 
                $link = str_replace(' ', '%20', $link); // Encode spaces
                if (!filter_var($link, FILTER_VALIDATE_URL)) { 
                    $_SESSION['error'] = 'Invalid link detected!';
                    header('Location: ../frontend/edit_song.php');
                    exit();
                }
            }
        }

        $sql_UpdateSong = "UPDATE songManagement 
                    SET songName = ?, artistID = ?, version = ?, mp3Link = ?, spotifyLink = ?, youtubeMusicLink = ?, songCoverPicture = ? 
                    WHERE songID = ?";

        $stmt = mysqli_prepare($connection, $sql_UpdateSong);

        if (!$stmt) {
            die("Database error: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt, "sisssssi", $songName, $artistID, $version, $mp3Link, $spotifyLink, $youtubeMusicLink, $songCoverPictureLink, $songID);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($connection);

            $_SESSION['success'] = 'Song successfully edited!';
            header('Location: ../frontend/songManagement.php');
            exit();
        } else {
            mysqli_stmt_close($stmt);
            mysqli_close($connection);

            $_SESSION['error'] = 'Error editing song!';
            header('Location: ../frontend/songManagement.php');
            exit();
        }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/edit_song.php');
    exit();
}
?>

?>