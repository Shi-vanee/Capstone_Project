<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify all required files exist
$required_files = [
    'session_handler.php',
    'connect.php',
    'vendor/autoload.php',
    'email_config.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        die("Missing required file: $file");
    }
}

// Include required files
require 'session_handler.php';
require 'connect.php';
require 'vendor/autoload.php';
require 'email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        if (empty($_POST['email'])) {
            throw new Exception("Email is required");
        }
        if (empty($_POST['role'])) {
            throw new Exception("Role is required");
        }

        $email = trim($_POST['email']);
        $role = trim($_POST['role']);

        // Verify role is valid
        if (!in_array($role, ['user', 'doctor'])) {
            throw new Exception("Invalid role specified");
        }

        // Check if user exists
        $table = ($role === 'doctor') ? 'doctors' : 'users';
        $idColumn = ($role === 'doctor') ? 'DoctorID' : 'UserID';

        $stmt = $conn->prepare("SELECT $idColumn FROM $table WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("No account found with that email address");
        }

        $user = $result->fetch_assoc();
        $userId = $user[$idColumn];

        // Generate secure token
        $token = bin2hex(random_bytes(16));
        $tokenHash = hash("sha256", $token);
        $expiry = date("Y-m-d H:i:s", time() + PASSWORD_RESET_EXPIRY);

        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token_hash, role, expiry_date) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param("ssss", $email, $tokenHash, $role, $expiry);
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        // Send email
        $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=$token&role=$role";
        
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;

            // Recipients
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request - Trust MediCare';
            
            $mail->Body = "<p>Please click <a href=\"$resetLink\">here</a> to reset your password.</p>";
            $mail->AltBody = "Reset your password at: $resetLink";

            $mail->send();
            $_SESSION['status'] = "Password reset link has been sent to your email";
            header("Location: login.php");
            exit();
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            throw new Exception("Failed to send email. Please try again later.");
        }

    } catch (Exception $e) {
        $_SESSION['status'] = $e->getMessage();
        header("Location: forgot_password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <!-- Your CSS includes here -->
</head>

<body>
    <!-- Your HTML form here -->
    <?php if (!empty($_SESSION['status'])): ?>
    <div class="alert"><?= htmlspecialchars($_SESSION['status']) ?></div>
    <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <form method="POST">
        <!-- Your form fields here -->
    </form>
</body>

</html>