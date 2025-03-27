<?php include '../backend/checkSession.php'; ?>

<?php
include '../backend/connection.php';

$userID = $_SESSION['userID'];
$username = $_SESSION['username'];

$sql = "SELECT email, created_at
        FROM userManagement
        WHERE userID = ?";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $email, $created_at);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

// Count number of quiz played
$quizCountSql = "SELECT COUNT(*) / 5 AS totalQuizzesPLayed
                 FROM quizManagement 
                 WHERE userID = ?";

$stmtQuizCount = mysqli_prepare($connection, $quizCountSql);
mysqli_stmt_bind_param($stmtQuizCount, "i", $userID);
mysqli_stmt_execute($stmtQuizCount);
mysqli_stmt_bind_result($stmtQuizCount, $totalQuizzesPlayed);
mysqli_stmt_fetch($stmtQuizCount);

mysqli_stmt_close($stmtQuizCount);

// Count the time played by the user
$totalTimePlayedSql = "SELECT SUM(totalTimeUsed) AS totalTimePlayed
                        FROM (
                            SELECT DISTINCT quizSession, totalTimeUsed
                            FROM quizManagement
                            WHERE userID = ?
                        ) AS uniqueSessions";

$stmtTotalTimePlayed = mysqli_prepare($connection, $totalTimePlayedSql);
mysqli_stmt_bind_param($stmtTotalTimePlayed, "i", $userID);
mysqli_stmt_execute($stmtTotalTimePlayed);
mysqli_stmt_bind_result($stmtTotalTimePlayed, $totalTimePlayed);
mysqli_stmt_fetch($stmtTotalTimePlayed);

mysqli_close($connection);
mysqli_stmt_close($stmtTotalTimePlayed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/profile.css">

</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>
    <?php include 'header.html'; ?>

    <button class="back-button" onclick="history.back()">Back</button>

    <main>
        <div class="hero-section">
            <div class="profile-card-buttons">
                <button class="hero-button active" id="profile-btn">Profile</button>
            </div>

            <!-- Profile Card -->
            <div class="profile-card" id="profile-card" style="display: block;">
                <div class="profile-header">
                    <h1><?php echo $username; ?></h1>
                    <div class="profile-pic-container">
                        <div class="profile-pic"><img src="images/avatar.jpg" alt="User Avatar" class="user-icon-settings"/></div>
                    </div>
                </div>

                <div class="profile-details">
                    <p><strong>Email: </strong><?php echo $email; ?></p>
                    <p><strong>Total quiz played: </strong><?php echo round($totalQuizzesPlayed, 2); ?></p>
                    <p><strong>Total time played: </strong><?php echo round($totalTimePlayed, 2); ?> seconds</p>
                    <p><strong>Created since: </strong><?php echo $created_at; ?></p>
                </div>
            </div>

            <div class="function-button-container">
                <button onclick="window.location.replace('../backend/processSignOut.php')" class="signout-button">Sign Out</button>                
                <button class="update-button">Update</button>
            </div>
        </div>
    </main>
    
    <?php include 'footer.html'; ?>

    <!-- Modal for Update -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="closeUpdateModal">&times;</span>

            <div class="modal-header">
                <h2>Update account details</h2>
            </div>
        
            <div class="modal-body">
                <p>Modify your account information easily and securely!</p>

                <form action="../backend/processUpdateProfile.php" method="POST">
                    <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" required/>
                    <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required/>
                    
                    <div class="modal-footer">
                        <button type="submit" name="btnUpdate">Confirm update</button>
                        <p>Reset password? <a href="#" id="resetPasswordLink">Here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Update Modal
            let updateModal = document.getElementById("updateModal");
            
            // Show modal when "Update button" is clicked
            document.querySelector('.update-button').addEventListener("click", function() {
                updateModal.style.display = 'block';
            });

            // Close modal when "X" is clicked
            document.getElementById('closeUpdateModal').addEventListener('click', function () {
                updateModal.style.display = 'none';
            });

            window.onclick = function (event) {
                if (event.target === updateModal) {
                    updateModal.style.display = 'none';
                }
            };
        });
    </script>
</body>
</html>