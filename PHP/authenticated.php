<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header('Location: already_login.php');  // Redirect to login page instead of homepage
    exit(0);
}
?>