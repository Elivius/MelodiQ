<?php include '../backend/checkSession.php';?>
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<?php
include '../backend/connection.php';

$sql = 'SELECT * FROM feedbackManagement';
$results = mysqli_query($connection, $sql);

if (!$results) {
    die('Database error: ' . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>

    <?php include 'header.html'; ?>

    <form action="../backend/processSelection.php" method="POST">

        <input type="hidden" name="managementType" value="feedbackManagement">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="container">

            <div class="header">
                <h1 class="underline">Feedback Management</h1>
                <div class="button-group">
                    <div class="left-buttons">
                        <button type="submit" name="action" value="delete">Delete</button>
                        
                    </div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Feedback ID</th>
                        <th>User ID</th>
                        <th>Message</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><input type="checkbox" name="selectedID[]" value="<?php echo $row['feedbackID']; ?>"></td>
                        <td><?php echo $row['feedbackID']; ?></td>
                        <td><?php echo $row['userID']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <div class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </div>
    <script src="filterFeedback.js"></script>
</body>
</html>
