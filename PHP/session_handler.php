<?php
// Check if session is already active
if (session_status() === PHP_SESSION_NONE) {
    // Set session cookie parameters before starting the session
    $cookie_lifetime = 15 * 60; // 15 minutes cookie duration
    session_set_cookie_params([
        'lifetime' => $cookie_lifetime,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'SameSite' => 'Lax'
    ]);
    
    // Start the session
    session_start();
}

// Session timeout configuration (15 minutes)
$session_timeout = 15 * 60; // 15 minutes in seconds

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // Session expired, destroy session and redirect to login page
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Update the last activity time on each page request
$_SESSION['last_activity'] = time();
?>