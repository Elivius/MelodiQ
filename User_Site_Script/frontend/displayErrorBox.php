<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>

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

        if (errorModal) {
            errorModal.style.display = "flex";
        };

        function closeError() {
            errorModal.style.display = "none";
        }

        if (errorModalCloseButton) {
            errorModalCloseButton.addEventListener("click", closeError);        
        };
    </script>
</body>
</html>
