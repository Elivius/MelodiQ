<?php include '../backend/checkSession.php'; ?>
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<?php
include '../backend/connection.php';
$sql = 'WITH MonthlyStats AS (
            -- Calculate average daily visitors and average time spent per month
            SELECT 
                DATE_FORMAT(q.date_played, "%Y-%m") AS month, 
                COUNT(DISTINCT q.userID) / COUNT(DISTINCT DATE(q.date_played)) AS avg_daily_visitors,
                AVG(q.totalTimeUsed) AS avg_time_spent
            FROM (
                -- Get distinct quiz sessions to avoid overcounting (one session per user per play)
                SELECT DISTINCT quizSession, userID, date_played, totalTimeUsed
                FROM quizmanagement
            ) q
            GROUP BY month
        ),
        ArtistPopularity AS (
            -- Count how many times each artist songs were used in quizzes (avoiding duplicate quizSession)
            SELECT 
                a.artistID,
                a.name,
                COALESCE(COUNT(DISTINCT q.quizSession), 0) AS quiz_usage_count  -- Ensure 0 for artists never played
            FROM artistmanagement a
            LEFT JOIN songmanagement s ON a.artistID = s.artistID
            LEFT JOIN quizmanagement q ON s.songID = q.songID
            GROUP BY a.artistID, a.name
        ),
        PopularArtists AS (
            -- Find the most popular artist each month
            SELECT 
                DATE_FORMAT(q.date_played, "%Y-%m") AS month,
                a.name AS most_popular_artist
            FROM quizmanagement q
            JOIN songmanagement s ON q.songID = s.songID
            JOIN artistmanagement a ON s.artistID = a.artistID
            GROUP BY month, a.artistID
            ORDER BY COUNT(DISTINCT q.quizSession) DESC
            LIMIT 1
        ),
        LeastPopularArtists AS (
            -- Find the least popular artist each month, prioritizing artists with 0 plays
            SELECT 
                m.month,
                COALESCE(a.name, "No Plays Recorded") AS least_popular_artist
            FROM (
                SELECT DISTINCT DATE_FORMAT(date_played, "%Y-%m") AS month FROM quizmanagement
            ) m
            CROSS JOIN artistmanagement a  -- Ensure all artists are considered
            LEFT JOIN songmanagement s ON a.artistID = s.artistID
            LEFT JOIN quizmanagement q ON s.songID = q.songID AND DATE_FORMAT(q.date_played, "%Y-%m") = m.month
            GROUP BY m.month, a.artistID, a.name
            ORDER BY COUNT(DISTINCT q.quizSession) ASC, a.artistID ASC
            LIMIT 1
        )
        -- Final report combining all stats
        SELECT 
            ms.month, 
            ROUND(ms.avg_daily_visitors, 2) AS average_daily_visitor, 
            ROUND(ms.avg_time_spent, 2) AS average_time_spent, 
            pa.most_popular_artist, 
            lpa.least_popular_artist
        FROM MonthlyStats ms
        LEFT JOIN PopularArtists pa ON ms.month = pa.month
        LEFT JOIN LeastPopularArtists lpa ON ms.month = lpa.month
        ORDER BY ms.month DESC';

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
    <title>Analytics and Report</title>
    <link rel="icon" type="image/png" href="images/melodiq.jpg">
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <?php include 'header.html'; ?>
    
    <div class="container">

        <div class="header">
            <h1 class="underline">Analytics and Report</h1>
            <div class="button-group">
                <div class="left-buttons">
                <select id="monthFilter">
                    <option value="all">Month: All</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                </div>
                <div class="right-buttons">
                    <button>Export</button>
                </div>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Month</th>
                        <th>Average Daily Visitor</th>
                        <th>Average Time Spent</th>
                        <th>Most Popular Artist</th>
                        <th>Least Popular Artist</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><input type="checkbox" name="selectedID[]" value="<?php echo $row['month']; ?>"></td>
                        <td><?php echo $row['month']; ?></td>
                        <td><?php echo $row['average_daily_visitor']; ?></td>
                        <td><?php echo $row['average_time_spent']; ?></td>
                        <td><?php echo $row['most_popular_artist']; ?></td>
                        <td><?php echo $row['least_popular_artist']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
        © 2024 MelodiQ Limited, All Rights Reserved
    </div>
    <script src="filterMonth.js"></script>
</body>
</html>
