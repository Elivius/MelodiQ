<?php include '../backend/checkSession.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles3.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>

    <?php include 'header.html'; ?>

    <main class="content">
        <div class="modal">
            <div class="modal-header">
                <h2>Add User</h2>
                <a href="userManagement.php" class="close-btn" aria-label="Close Modal">&times;</a>
            </div>
            <form class="modal-body" action="../backend/processAddUser.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="statusInput" name="status" value="Active">

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter username" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter password" required>
                            <span class="toggle-password" role="button" aria-label="Show Password" onclick="togglePassword('password', this)">👁️</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm-password" name="passwordConfirmation" placeholder="Confirm password" required>
                            <span class="toggle-password" role="button" aria-label="Show Password" onclick="togglePassword('confirm-password', this)">👁️</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <div>
                            <button type="button" class="status-btn active" onclick="setStatus(this, 'Active')">Active</button>
                            <button type="button" class="status-btn" onclick="setStatus(this, 'Inactive')">Inactive</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-btn" name="submit-btn">Add User</button>
            </form>
        </div>
    </main>
    <footer class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </footer>
    <script src="script2.js"></script>
</body>
</html>
