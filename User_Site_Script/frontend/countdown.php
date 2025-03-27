<?php include '../backend/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MelodiQ</title>

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    
    <style>
        .hero {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1e1e2f;
            color: white;
            text-align: center;
        }

        #countdown {
            font-size: 8rem;
            font-weight: bold;
            background:white;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

</head>
<body>
    <?php include 'header.html'; ?>

    <audio src="sound_effect/countdown.mp3" autoplay preload="auto"></audio>

    <div id="countdown" class="hero">3</div>

    <?php include 'footer.html'; ?>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var isLeaving = false;  // Declare the flag to track if the user is leaving

            function startCountdown() {
                var countdownElement = document.getElementById("countdown");
                var count = 3;

                var interval = setInterval(function() {
                    if (count > 1) {
                        count--;
                        countdownElement.innerText = count;
                    } else {
                        countdownElement.innerText = "Go!";
                        clearInterval(interval);

                        // Redirect after 1 second
                        setTimeout(function() {
                            isLeaving = true;  // Set isLeaving to true when redirection occurs
                            window.location.replace("quiz.php");
                        }, 1000);
                    }
                }, 1000);
            }

            startCountdown();

            window.addEventListener("beforeunload", function() {
                if (!isLeaving) {
                    navigator.sendBeacon("../backend/processQuitGame.php");  // Only send beacon if the user is not leaving
                }
            });
        });
    </script>


</body>
</html>
