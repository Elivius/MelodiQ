<?php include '../backend/checkSession.php';?>
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<?php
include '../backend/connection.php';

$sql = 'SELECT * FROM artistManagement';
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
    <title>Artist Management</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>

    <?php include 'header.html'; ?>

    <form action="../backend/processSelection.php" method="POST">

        <input type="hidden" name="managementType" value="artistManagement">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="container">
            <div class="header">
                <h1 class="underline">Artist Management</h1>
                <div class="button-group">
                    <div class="left-buttons">
                        <button type="submit" name="action" value="edit">Edit</button>
                        <button type="submit" name="action" value="delete">Delete</button>
                    </div>

                    <div class="right-buttons">
                        <button type="submit" name="action" value="add">Add</button>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Artist ID</th>
                        <th>Name</th>
                        <th>Picture Link</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><input type="checkbox" name="selectedID[]" value="<?php echo $row['artistID']; ?>"></td>
                        <td><?php echo $row['artistID']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['picture']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </form>


    <div class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </div>
    <script src="script2.js"></script>
</body>
</html>