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

function setVersion(button, version) {
    const buttons = document.querySelectorAll('.version-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    document.getElementById('versionInput').value = version;
    console.log(`User status set to: ${version}`);
}

function setStatus(button, status) {
    const buttons = document.querySelectorAll('.status-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    document.getElementById('statusInput').value = status;
    console.log(`User status set to: ${status}`);
}

// Select all checkbox function
document.getElementById("selectAll").addEventListener("change", function() {
    const checkboxes = document.querySelectorAll("input[name='selectedID[]']");
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
