<?php include '../backend/checkSession.php';?>

<?php
include '../backend/connection.php';

$sql = 'SELECT * FROM userManagement';
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
    <title>User Management</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>

    <?php include 'header.html'; ?>

    <form action="../backend/processSelection.php" method="POST">

        <input type="hidden" name="managementType" value="userManagement">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="container">

            <div class="header">
                <h1 class="underline">User Management</h1>
                <div class="button-group">
                    <div class="left-buttons">
                        <button type="submit" name="action" value="edit">Edit</button>
                        <button type="submit" name="action" value="delete">Delete</button>

                        <select id="statusFilter" name="statusFilter">
                            <option value="All">Status: All</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="right-buttons">
                        <button type="submit" name="action" value="add">Add</button>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($results)): ?>
                        <tr>
                            <td><input type="checkbox" name="selectedID[]" value="<?php echo $row['userID']; ?>"></td>
                            <td><?php echo $row['userID']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </div>
    <script src="filterUser.js"></script>
</body>
</html>
