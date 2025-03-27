<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

if (isset($_POST['submit-btn']) && $_POST['songName'] && $_POST['artistID'] && $_POST['mp3Link'] 
    && $_POST['spotify'] && $_POST['youtubeMusic'] && $_POST['songCoverPictureLink'] && $_POST['version']) {
    
    $songName = trim($_POST['songName']);
    $artistID = trim($_POST['artistID']);
    $version = trim($_POST['version']);
    $mp3Link = trim($_POST['mp3Link']);
    $spotify = trim($_POST['spotify']);
    $youtubeMusic = trim($_POST['youtubeMusic']);
    $songCoverPictureLink = trim($_POST['songCoverPictureLink']);

    // Check if is valid URL
    $links = [$mp3Link, $spotify, $youtubeMusic, $songCoverPictureLink];
    
    foreach ($links as &$link) { // Use reference to update original values
        if (!empty($link)) { 
            $link = str_replace(' ', '%20', $link); // Encode spaces
            if (!filter_var($link, FILTER_VALIDATE_URL)) { 
                $_SESSION['error'] = 'Invalid link detected!';
                header('Location: ../frontend/add_song.php');
                exit();
            }
        }
    }

    $sql_InsertSong = "INSERT INTO songManagement 
                        (songName, artistID, version, mp3Link, spotifyLink, youtubeMusicLink, songCoverPicture) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtSong = mysqli_prepare($connection, $sql_InsertSong);

    if (!$stmtSong) {
        die("Database error: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmtSong, "sisssss", $songName, $artistID, $version, $mp3Link, $spotify, $youtubeMusic, $songCoverPictureLink);

    // Execute the statement
    if (mysqli_stmt_execute($stmtSong)) {
        mysqli_stmt_close($stmtSong);
        mysqli_close($connection);

        $_SESSION['success'] = 'Successfully added song!';
        header('Location: ../frontend/songManagement.php');
        exit();
    } else {
        mysqli_stmt_close($stmtArtist);
        mysqli_close($connection);

        $_SESSION['error'] = 'Error adding artist!';
        header('Location: ../frontend/songManagement.php');
        exit();
    }

} else {
    $_SESSION['error'] = 'Please fill in all details!';
    header('Location: ../frontend/add_song.php');
    exit();
}
?>