<?php include '../backend/checkSession.php'; ?>

<?php
include '../backend/connection.php';

$artistID = $_GET['selectedID'];

$sql = "SELECT name, picture FROM artistManagement
        WHERE artistID = ?";

$stmt = mysqli_prepare($connection, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "i", $artistID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $picture);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    $_SESSION['error'] = 'An error occured. Please try again.';
    header('Location: ../frontend/artistManagement.php');
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
    <title>Add Artist</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles3.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>

    <?php include 'header.html'; ?>
    
    <main class="content">
        <div class="modal">
            <div class="modal-header">
                <h2>Edit Artist</h2>
                <a href="artistManagement.php" class="close-btn" aria-label="Close Modal">&times;</a>
            </div>
            <form class="modal-body" action="../backend/processEditArtist.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="artistID" value="<?php echo $artistID; ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="artistName" name="name" placeholder="Enter artist name" value="<?php echo htmlspecialchars($name); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Picture Link</label>
                        <input type="text" id="pictureLink" name="pictureLink" placeholder="Enter picture link" value="<?php echo htmlspecialchars($picture); ?>" required>
                    </div>
                </div>
                <button type="submit" class="submit-btn" name="save-btn">Confirm Changes</button>
            </form>
        </div>
    </main>
    <footer class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </footer>
    <script src="script2.js"></script>
</body>
</html>
