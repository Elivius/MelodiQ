<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>

    <div class="container">
        <div class="header">
            <img src="images/melodiq.jpg" class="logo-img" alt="Logo">
        </div>
        <div class="content">
            <div class="logo">
                <img src="images/melodiq.jpg" class="logo-img" alt="Logo">
            </div>
            <div class="login-form">
                <h2>Log in</h2>
                <p>Log in to see your stats!</p>
                <form id="loginForm" action="../backend/processLogin.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                    <button type="submit" name="btnLogin-admin">Log in</button>
                </form>
            </div>
        </div>
        <div class="footer">
            © 2024 MelodiQ Limited. All Rights Reserved
        </div>
    </div>
</body>
</html>

