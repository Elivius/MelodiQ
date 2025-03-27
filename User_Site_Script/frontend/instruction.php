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

        <section id="instruction" class="information">
            <div class="information-container">
                <h2 class="information-title">Instructions</h2>
                <hr class="title-underline">
                <h3>How to play:</h3>
                <br>
                <ul class="instruction-list">
                    <li><b>Select an Artist</b> - Choose your favorite artist or band.</li>
                    <br>
                    <li><b>Personalize Your Quiz Experience</b> - Select the level of difficulty.</li>
                    <br>
                    <li><b>Guess the Song</b> - Based on the audio clips, figure out the correct song title.</li>
                    <br>
                    <li><b>Beat the Clock</b> - Answer all five questions correctly to score the highest. If multiple players have the same score, the fastest one ranks higher!</li>
                    <br>
                    <li><b>Play & Compete</b> - Challenge yourself or compete with friends to see who knows the lyrics best!</li>
                </ul>
            </div>
        </section>
    </main>
    
    <!-- Insert footer.html -->
    <?php include 'footer.html'; ?>

    <script>
        function showAlert(event) {
            event.preventDefault(); 
    
            if (window.location.hash === "#instruction") {
                alert("Already on the Instruction page");
            } else {
                window.location.hash = "instruction"; 
            }
        }
    </script>
    
      
    
</body>
</html>