function handleLogin(event) {
    event.preventDefault(); // Prevent form from submitting the traditional way
    
    // Simulate login validation (you can add actual login logic here)
    const username = document.getElementById('username-field').value;
    const password = document.getElementById('password-field').value;
    
    if (username === 'covai' && password === 'Azad@0606') {
        // Redirect to index.html after successful login
        window.location.href = 'dashboard.html';
    } else {
        alert('Invalid credentials');
    }
}

function togglePassword() {
    const passwordField = document.getElementById('password-field');
    const toggleButton = document.getElementById('toggle-password');
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "Hide";
    } else {
        passwordField.type = "password";
        toggleButton.textContent = "Show";
    }
}