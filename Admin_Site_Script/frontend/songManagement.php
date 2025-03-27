<?php include '../backend/checkSession.php';?>
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<?php
include '../backend/connection.php';

$sql = 'SELECT * FROM songManagement';
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
    <title>Song Management</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>

    <?php include 'header.html'; ?>

    <form action="../backend/processSelection.php" method="POST">

        <input type="hidden" name="managementType" value="songManagement">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="container">

            <div class="header">
                <h1 class="underline">Song Management</h1>
                <div class="button-group">
                    <div class="left-buttons">
                        <button type="submit" name="action" value="edit">Edit</button>
                        <button type="submit" name="action" value="delete">Delete</button>

                        <select id="versionFilter" name="versionFilter">
                            <option value="All">Version: All</option>
                            <option value="Regular">Regular</option>
                            <option value="Karaoke">Karaoke</option>
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
                            <th><input type= "checkbox" id="selectAll"></th>
                            <th>Song ID</th>
                            <th>Song Name</th>
                            <th>Artist ID</th>
                            <th>Version</th>
                            <th>mp3 Link</th>
                            <th>Spotify Link</th>
                            <th>Youtube Music Link</th>
                            <th>Song Cover Picture</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($results)): ?>
                        <tr>
                            <td><input type="checkbox" name="selectedID[]" value="<?php echo $row['songID']; ?>"></td>
                            <td><?php echo $row['songID']; ?></td>
                            <td><?php echo $row['songName']; ?></td>
                            <td><?php echo $row['artistID']; ?></td>
                            <td><?php echo $row['version']; ?></td>
                            <td><?php echo $row['mp3Link']; ?></td>
                            <td><?php echo $row['spotifyLink']; ?></td>
                            <td><?php echo $row['youtubeMusicLink']; ?></td>
                            <td><?php echo $row['songCoverPicture']; ?></td>
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
    <script src="filterSong.js"></script>
</body>
</html>
