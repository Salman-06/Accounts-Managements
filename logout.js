// Ensure the function is defined globally in app.js
function handleLogout() {
    const confirmation = confirm("Are you sure you want to logout?");
    if (confirmation) {
      window.location.href = 'index.html'; // Redirect to login page
    }
  }