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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>    
    <?php include 'displayErrorBox.php'; ?>
    <?php include 'displaySuccessBox.php'; ?>
    <?php include 'header.html'; ?>
    
    <button class="back-button" onclick="history.back()">Back</button>
    
    <main>
        <section class="hero">
            <h1>Welcome to MelodiQ</h1>
            <p>Your ultimate music experience starts here.</p>
        </section>

        <section id="feedback" class="information">
            <div class="information-container">
                <h2 class="information-title">Leave Feedback</h2>
                <hr class="title-underline">
            </div>          
            <form class="feedback-form" action="../backend/processFeedback.php" method="POST">
                <textarea class="feedback-textarea" name="feedback" placeholder="Write your feedback here..."></textarea>
                <button type="submit" class="submit-button" name="btnSubmit">Submit</button>
            </form>

        </section>
    </main>
    
    <?php include 'footer.html'; ?>
</body>
</html>
