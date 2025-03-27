<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>

    <link rel="stylesheet" href="css/success_box.css">

</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-modal" class="success-modal" data-error="<?php echo $_SESSION['success']; ?>">
        <div class="success-box">
            <p>
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
            </p>
            <img src="images/tick.png" alt="Success">
            <br>
            <button onclick="closeSuccess()">Close</button>
        </div>
        </div>
    <?php endif; ?>

    <script>
        let successModal = document.getElementById("success-modal");
        let successMessage = successModal ? successModal.getAttribute("data-success") : "";
        let successModalCloseButton = document.querySelector(".success-box button");

        // Close Success box
        if (successModal) {
            successModal.style.display = "flex";
        };

        function closeSuccess() {
            successModal.style.display = "none";
        }

        if (successModalCloseButton) {
            successModalCloseButton.addEventListener("click", successError);        
        };
    </script>
</body>
</html>
