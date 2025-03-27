<?php
session_start();

// var_dump($_SESSION);

if (!isset($_SESSION['userID']) || ($_SESSION['status'] != 'Admin') || (!isset($_SESSION['csrf_token']))) {
    header('Location: ../frontend/login.php');
    exit();
}
?>