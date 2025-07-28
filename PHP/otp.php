<?php
session_start();
include('connect.php'); // Connect to the database

if (!isset($_SESSION['email'])) {
    $_SESSION['status'] = "Access denied. Please register or login.";
    header("Location: register.php");
    exit(0);
}

// Function to generate and send OTP
function generateAndSendOTP($email, $role, $conn) {
    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    
    // Determine the table based on role
    $table = $role === 'doctor' ? 'doctors' : 'users';
    
    // Update OTP in database
    $update_query = "UPDATE $table SET otp_code = '$otp' WHERE email = '$email'";
    if (!mysqli_query($conn, $update_query)) {
        return false;
    }
    
    // Send OTP via email (you'll need to implement your email sending function)
    $subject = "Your Trust MediCare OTP Code";
    $message = "Your OTP code is: $otp\n\nThis code will expire in 10 minutes.";
    $headers = "From: no-reply@trustmedicare.com";
    
    // In a real application, use a proper email sending function
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Handle OTP resend request
if (isset($_GET['resend_otp'])) {
    if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
        $email = $_SESSION['email'];
        $role = $_SESSION['role'];
        
        if (generateAndSendOTP($email, $role, $conn)) {
            $_SESSION['otp_message'] = "A new OTP has been sent to your email.";
        } else {
            $_SESSION['otp_error'] = "Failed to resend OTP. Please try again.";
        }
        header("Location: otp.php");
        exit();
    }
}

// Handle OTP verification
if (isset($_POST['verifyOtp'])) {
    $email = $_SESSION['email'];
    $otp = htmlspecialchars($_POST['otp']);

    // Determine which table to query based on the role
    $table = isset($_SESSION['role']) && $_SESSION['role'] === 'doctor' ? 'doctors' : 'users';

    // Check the OTP from the database
    $query = "SELECT otp_code FROM $table WHERE email='$email' LIMIT 1";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $db_otp = $row['otp_code'];

        if ($db_otp == $otp) {
            // Update OTP verification status
            $update_query = "UPDATE $table SET verify_status= 1 WHERE email='$email'";
            mysqli_query($conn, $update_query);

            // Clear session variables and redirect to login page
            unset($_SESSION['email']);
            unset($_SESSION['role']);
            $_SESSION['status'] = "OTP verified successfully! You can now log in.";
            header("Location: login.php");
            exit(0);
        } else {
            // Invalid OTP entered
            $_SESSION['otp_error'] = "Invalid OTP. Please try again.";
            header("Location: otp.php");
            exit(0);
        }
    } else {
        // Email not found in the database
        $_SESSION['otp_error'] = "Invalid request or email not found.";
        header("Location: otp.php");
        exit(0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Trust MediCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Roboto', sans-serif;
    }

    .otp-container {
        max-width: 450px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .otp-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .otp-header img {
        width: 80px;
        margin-bottom: 15px;
    }

    .otp-header h2 {
        color: #333;
        font-weight: 600;
    }

    .otp-message {
        text-align: center;
        margin-bottom: 25px;
        color: #555;
    }

    .otp-input-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        border: 2px solid #ddd;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .otp-input:focus {
        border-color: #c64f00;
        box-shadow: 0 0 5px rgba(198, 79, 0, 0.3);
    }

    .btn-verify {
        background-color: #c64f00;
        border: none;
        width: 100%;
        padding: 12px;
        font-weight: 600;
        margin-top: 10px;
    }

    .btn-verify:hover {
        background-color: #b34700;
    }

    .resend-link {
        text-align: center;
        margin-top: 20px;
    }

    .resend-link a {
        color: #c64f00;
        text-decoration: none;
    }

    .alert {
        margin-bottom: 25px;
    }

    #countdown {
        color: #666;
        font-size: 0.9em;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="otp-container">
            <div class="otp-header">
                <img src="images/logo.jpg" alt="Trust MediCare Logo">
                <h2>OTP Verification</h2>
            </div>

            <?php if (isset($_SESSION['otp_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['otp_error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['otp_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['otp_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['otp_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['otp_message']); ?>
            <?php endif; ?>

            <div class="otp-message">
                <p>We've sent a 6-digit verification code to your email:</p>
                <p class="fw-bold"><?= isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?></p>
                <p>Please enter it below to verify your account.</p>
            </div>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP Code</label>
                    <input type="number" class="form-control form-control-lg text-center" id="otp" name="otp"
                        placeholder="------" required pattern="[0-9]{6}" title="Please enter a 6-digit OTP">
                </div>

                <button type="submit" name="verifyOtp" class="btn btn-primary btn-verify">
                    <i class="fas fa-check-circle me-2"></i> Verify OTP
                </button>
            </form>

            <div class="resend-link">
                <p>Didn't receive the code?
                    <a href="otp.php?resend_otp=1" id="resendLink">Resend OTP</a>
                    <span id="countdown"></span>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto-focus on OTP input
    document.getElementById('otp').focus();

    // Resend OTP countdown timer
    let timeLeft = 60; // 60 seconds cooldown
    const countdownElement = document.getElementById('countdown');
    const resendLink = document.getElementById('resendLink');

    function updateCountdown() {
        if (timeLeft <= 0) {
            countdownElement.textContent = '';
            resendLink.style.display = 'inline';
            return;
        }

        countdownElement.textContent = ` (wait ${timeLeft}s)`;
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    }

    // Initially hide the resend link and start countdown
    resendLink.style.display = 'none';
    updateCountdown();

    // After countdown finishes, show the resend link
    setTimeout(() => {
        resendLink.style.display = 'inline';
    }, timeLeft * 1000);
    </script>
</body>

</html>