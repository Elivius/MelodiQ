<?php include '../backend/checkSession.php';?>

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
                <h2>Add Artist</h2>
                <a href="artistManagement.php" class="close-btn" aria-label="Close Modal">&times;</a>
            </div>
            <form class="modal-body" action="../backend/processAddArtist.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="artistName" name="name" placeholder="Enter artist name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Picture Link</label>
                        <input type="text" id="pictureLink" name="pictureLink" placeholder="Enter picture link" required>
                    </div>
                </div>
                <button type="submit" class="submit-btn" name="submit-btn">Add Artist</button>
            </form>
        </div>
    </main>
    <footer class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </footer>
    <script src="script2.js"></script>
</body>
</html>
