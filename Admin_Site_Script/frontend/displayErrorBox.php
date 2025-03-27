<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="css/error_box.css">

</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div id="error-modal" class="error-modal" data-error="<?php echo $_SESSION['error']; ?>">
        <div class="error-box">
            <p>
            <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
            </p>
            <img src="images/exclamation.png" alt="Error">
            <br>
            <button onclick="closeError()">Close</button>
        </div>
        </div>
    <?php endif; ?>

    <script>
        let errorModal = document.getElementById("error-modal");
        let errorMessage = errorModal ? errorModal.getAttribute("data-error") : "";
        let errorModalCloseButton = document.querySelector(".error-box button");

        // Close Error box
        if (errorModal) {
            errorModal.style.display = "flex";
        };

        function closeError() {
            errorModal.style.display = "none";

            // Won't reopen the sign up modal if the error is caused due to email exist but password incorrect
            if (errorMessage !== 'Login failed. Please check again your email and password!') {
            signUpModal.style.display = 'block';
            };
        }

        if (errorModalCloseButton) {
            errorModalCloseButton.addEventListener("click", closeError);        
        };
    </script>
</body>
</html>
