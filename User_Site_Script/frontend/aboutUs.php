<?php include '../backend/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/help.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Insert header.html -->
    <?php include 'header.html'; ?>
    
    <button class="back-button" onclick="history.back()">Back</button>
    
    <main>
        <section class="hero">
            <h1>Welcome to MelodiQ</h1>
            <p>Your ultimate music experience starts here.</p>
        </section>

        <section id="aboutUs" class="information">
            <div class="information-container">
                <h2 class="information-title">About Us</h2>
                <hr class="title-underline">
                <p>“MelodiQ” is an interactive web-based quiz designed to challenge users’ music knowledge by having them guess songs based on short audio clips. This project aims to provide an engaging, responsive platform where users can test their familiarity with a wide variety of music genres. 
The primary goal of “MelodiQ” is to create a fun and immersive experience for music enthusiasts of all ages, encouraging them to discover new songs and revisit old favourites. Through a combination of audio-based questions, intuitive design, and gamification features, this platform will offer various difficulty levels and categories, allowing users to personalize their quiz experience. The website will dynamically pull questions from a song database, track user scores, and feature a leaderboard to promote friendly competition.
</p>
            </div>
        </section>
    </main>
    
    <!-- Insert header.html -->
    <?php include 'footer.html'; ?>
    
</body>
</html>