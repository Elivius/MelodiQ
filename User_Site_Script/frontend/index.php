<?php session_start();?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MelodiQ</title>

  <link rel="icon" type="image/png" href="images/logo.png">
  <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
  <?php include 'displayErrorBox.php'; ?>
  <?php include 'displaySuccessBox.php'; ?>

  <header>
    <div class="logo"></div>

    <div class="header-right">

      <div class="dropdown">
        <button style="background: none; border: none; color: white; font-size: 16px; cursor: pointer;">Log in / Sign
          up</button>
        <div class="dropdown-content login-dropdown">
          <h3>Log in</h3>
          <p>Log in to see your stats!</p>

          <form action="../backend/processLogin.php" method="POST">
            <input type="email" name="email" placeholder="Email"
                    value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required/>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="btnLogin">Log in</button>
          </form>

          <a href="#">Reset Password</a>
          <a href="#" id="signUpLink" class="account-options">No account? Sign up</a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <div class="main-logo"></div>
    <button class="play-button">Play Now</button>
  </main>

  <footer>
      <div class="footer-bottom">
          <p>© 2024 MelodiQ Limited. All Rights Reserved.</p>
      </div>
  </footer>

  <!-- Modal for Login -->
  <div id="logInModal" class="modal">
    <div class="modal-content">
      <span class="modal-close" id="closeLoginModal">&times;</span>

      <div class="modal-header">
        <h2>Log in</h2>
      </div>
      
      <div class="modal-body">
        <p>Log in to see your stats!</p>

        <form action="../backend/processLogin.php" method="POST">
            <input type="email" name="email" placeholder="Email"
                    value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required/>

            <div class="password-container">
              <input type="password" id="password" name="password" placeholder="Password" required>
              <i class="fa-solid fa-eye togglePassword"></i>
            </div>

            <div class="modal-footer">
              <button type="submit" name="btnLogin">Log in</button>
              <p>No account? <a href="#" id="signUpLink2">Sign up</a></p>
            </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Modal for Sign Up -->
  <div id="signUpModal" class="modal">
    <div class="modal-content">
      <span class="modal-close" id="closeSignUpModal">&times;</span>

      <div class="modal-header">
        <h2>Sign up</h2>
      </div>
      
      <div class="modal-body">
        <p>Sign up to track your progress!</p>

        <form action="../backend/processSignup.php" method="POST">
          <input type="text" name="username" placeholder="Username"
                  value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" required/>
          <input type="email" name="email" placeholder="Email"
                  value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required/>
          
          <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fa-solid fa-eye togglePassword"></i>
          </div>

          <div class="password-container">
            <input type="password" id="passwordConfirmation" name="passwordConfirmation" placeholder="Confirm Password" required>
            <i class="fa-solid fa-eye togglePassword"></i>
          </div>

          <div class="modal-footer">
            <button type="submit" name="btnSignUp">Sign up</button>
            <p>Got account? <a href="#" id="loginLink">Log in</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let signUpModal = document.getElementById("signUpModal");
      let logInModal = document.getElementById("logInModal");

      // Show modal when "Sign Up" is clicked from drop down login
      document.getElementById('signUpLink').addEventListener('click', function (event) {
        event.preventDefault();
        logInModal.style.display = 'none';
        signUpModal.style.display = 'block';
      });

      // Show modal when "Sign Up" is clicked from login form
      document.getElementById('signUpLink2').addEventListener('click', function (event) {
        event.preventDefault();
        logInModal.style.display = 'none';
        signUpModal.style.display = 'block';
      });

      // Show modal when "Log In" is clicked
      document.getElementById('loginLink').addEventListener('click', function (event) {
        event.preventDefault();
        logInModal.style.display = 'block';
        signUpModal.style.display = 'none';
      });

      // Show modal when "Play now button" is clicked
      document.querySelector('.play-button').addEventListener("click", function() {
        logInModal.style.display = 'block';
      });    

      // Close modal when "X" is clicked
      document.getElementById('closeSignUpModal').addEventListener('click', function () {
        signUpModal.style.display = 'none';
      });

      // Close modal when "X" is clicked
      document.getElementById('closeLoginModal').addEventListener('click', function () {
        logInModal.style.display = 'none';
      });

      // Close modal if clicked outside the modal content
      window.onclick = function (event) {
        // If clicked outside the signUpModal, close it
        if (event.target === signUpModal) {
          signUpModal.style.display = 'none';
        }
        // If clicked outside the logInModal, close it
        if (event.target === logInModal) {
          logInModal.style.display = 'none';
        }
      };
      
      // Toggle password visibility
      document.addEventListener("click", function (event) {
        if (event.target.classList.contains("togglePassword")) {
          let passwordField = event.target.previousElementSibling; // Selects the corresponding input field

          passwordField.type = passwordField.type === "password" ? "text" : "password";
          event.target.classList.toggle("fa-eye");
          event.target.classList.toggle("fa-eye-slash"); // Toggle icon
        }
      });
    }); 
  </script>
</body>

</html>