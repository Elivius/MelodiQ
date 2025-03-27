<?php
session_start();

// var_dump($_SESSION);

if (!isset($_SESSION['userID']) || ($_SESSION['status'] != 'Active')) {
    header('Location: ../frontend/index.php');
    exit();
}
?>