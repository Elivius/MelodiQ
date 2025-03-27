<?php
include 'connection.php';
session_start();

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Invalid CSRF Token';
    header('Location: ../frontend/login.php');
    exit();
}

$managementType = $_POST['managementType'];
$action = $_POST['action'];

// IF action is add
if ($action == 'add') {
    if ($managementType =='artistManagement') {
        header('Location: ../frontend/add_artist.php');
        exit();
    } elseif ($managementType =='songManagement') {
        header('Location: ../frontend/add_song.php');
        exit();
    } elseif ($managementType =='userManagement') {
        header('Location: ../frontend/add_user.php');
        exit();
    }
}

// IF action is not add or edit (which required selected ID)
$selectedID = $_POST['selectedID'];

if (!empty($selectedID) && is_array($selectedID)) {
    // Sanitize to make sure selectedID is only integers
    $selectedID = array_map('intval', $selectedID);

    if ($action == 'delete') {
        if ($managementType == 'artistManagement') {
            header('Location: processDeleteArtist.php?csrf_token=' . urlencode($_SESSION['csrf_token']) . '&selectedID=' . urlencode(implode(',', $selectedID)));
            exit();
        } elseif ($managementType == 'feedbackManagement') {
            header('Location: processDeleteFeedback.php?csrf_token=' . urlencode($_SESSION['csrf_token']) . '&selectedID=' . urlencode(implode(',', $selectedID)));
            exit();
        } elseif ($managementType == 'songManagement') {
            header('Location: processDeleteSong.php?csrf_token=' . urlencode($_SESSION['csrf_token']) . '&selectedID=' . urlencode(implode(',', $selectedID)));
            exit();
        } elseif ($managementType == 'userManagement') {
            header('Location: processDeleteUser.php?csrf_token=' . urlencode($_SESSION['csrf_token']) . '&selectedID=' . urlencode(implode(',', $selectedID)));
            exit();
        }
    } elseif ($action == 'edit') {

        if (count($selectedID) !== 1) {
            $_SESSION['error'] = 'You can only select one item for editing!';
            header('Location: ../frontend/' . $managementType . '.php');
            exit();
        }

        $targetID = $selectedID[0]; // Take only the first and only ID

        if ($managementType =='artistManagement') {
            header('Location: ../frontend/edit_artist.php?selectedID=' . urlencode($targetID));
            exit();
        } elseif ($managementType =='songManagement') {
            header('Location: ../frontend/edit_song.php?selectedID=' . urlencode($targetID));
            exit();
        } elseif ($managementType =='userManagement') {
            header('Location: ../frontend/edit_user.php?selectedID=' . urlencode($targetID));
            exit();
        }
    }

} else {
    $_SESSION['error'] = 'Please select a row before performing this function';
    header('Location: ../frontend/' . $managementType . '.php');
    exit();
}

mysqli_close($connection);
?>