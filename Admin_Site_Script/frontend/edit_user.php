<?php include '../backend/checkSession.php'; ?>

<?php
include '../backend/connection.php';

$userID = $_GET['selectedID'];

$sql = "SELECT email, username, status FROM userManagement
        WHERE userID = ?";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $email, $username, $status);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    $_SESSION['error'] = 'An error occured. Please try again.';
    header('Location: ../frontend/userManagement.php');
    exit();
}

if ($status == 'Admin') {
    $_SESSION['error'] = 'Editing admin details are not allowed!';
    header('Location: ../frontend/userManagement.php');
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
                <h2>Edit User</h2>
                <a href="userManagement.php" class="close-btn" aria-label="Close Modal">&times;</a>
            </div>
            <form class="modal-body" action="../backend/processEditUser.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="statusInput" name="status" value="Active">
                <input type="hidden" name="userID" value="<?php echo $userID; ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <div>
                            <button type="button" class="status-btn <?php echo ($status === 'Active') ? 'active' : ''; ?>" 
                                onclick="setStatus(this, 'Active')">Active</button>

                            <button type="button" class="status-btn <?php echo ($status === 'Inactive') ? 'active' : ''; ?>" 
                                onclick="setStatus(this, 'Inactive')">Inactive</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-btn" name="save-btn">Confirm Changes</button>
            </form>
        </div>
    </main>
    <footer class="footer">
        © 2024 MelodiQ Limited. All Rights Reserved
    </footer>
    <script src="script2.js"></script>
</body>
</html>
