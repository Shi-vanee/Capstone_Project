<?php
session_start();
include('connect.php'); // Connect to the database

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

/**
 * Sends an OTP to the user's email.
 */
function send_otp($fName, $lName, $email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'trustingmedicare@gmail.com'; // Your Gmail address
        $mail->Password   = 'wzwl gawx akij ixaz'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('trustingmedicare@gmail.com', 'Trust Medicare');
        $mail->addAddress($email, "$fName $lName");

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Trust Medicare Registration';
        $mail->Body    = "
            <h2>Hello $fName $lName,</h2>
            <p>Your OTP for completing your registration is:</p>
            <h3>$otp</h3>
            <p>Please enter this OTP on the website to verify your email.</p>
        ";

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false; // Email failed
    }
}

if (isset($_POST['signUp'])) {
    // Collect and sanitize user inputs
    $role = htmlspecialchars($_POST['role']);
    $fName = htmlspecialchars($_POST['fName']);
    $lName = htmlspecialchars($_POST['lName']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password_hash']);
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    // Check if the database connection is established
    if (!isset($conn)) {
        die("Database connection is not established.");
    }

    // Determine the appropriate table based on the role
    $table = ($role === 'doctor') ? 'doctors' : 'users';

    // Check if the email already exists in the table
    $check_email_query = "SELECT email FROM $table WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email ID already exists.";
        header("Location: login.php");
        exit(0);
    } else {
        // Hash the password before storing it
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $query = "INSERT INTO $table (fName, lName, email, password_hash, otp_code, verify_status, created_at) 
                  VALUES ('$fName', '$lName', '$email', '$passwordHash', '$otp', 0, NOW())";

        $query_run = mysqli_query($conn, $query);

        if ($query_run) {
            // Send OTP email
            if (send_otp($fName, $lName, $email, $otp)) {
                // Store email and role in session for OTP verification
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['status'] = "Registration successful! Please verify your email using the OTP sent.";
                header("Location: otp.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Failed to send OTP. Please try again.";
                header("Location: register.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Registration failed. Please try again.";
            header("Location: register.php");
            exit(0);
        }
    }
}
?>