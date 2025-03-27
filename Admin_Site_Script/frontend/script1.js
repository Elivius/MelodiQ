document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Login form submitted!");
});

function togglePassword(inputId, eyeIcon) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text"; // Show password
        eyeIcon.textContent = "🙈";
    } else {
        input.type = "password"; // Hide password
        eyeIcon.textContent = "👁️";
    }
}

function setStatus(button, status) {
    const buttons = document.querySelectorAll('.status-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    console.log(`User status set to: ${status}`);
}
