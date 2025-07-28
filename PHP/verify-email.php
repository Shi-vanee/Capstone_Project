<?php
session_start();
include('connect.php');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']); // Sanitize token

    // Check for the token in the `users` table
    $verify_query_user = "SELECT verify_token, verify_status FROM users WHERE verify_token = '$token' LIMIT 1";
    $verify_query_doctor = "SELECT verify_token, verify_status FROM doctors WHERE verify_token = '$token' LIMIT 1";

    $verify_query_run_user = mysqli_query($conn, $verify_query_user);
    $verify_query_run_doctor = mysqli_query($conn, $verify_query_doctor);

    if (mysqli_num_rows($verify_query_run_user) > 0) {
        $row = mysqli_fetch_assoc($verify_query_run_user);

        if ($row['verify_status'] == "0") {
            // Update user verification status
            $update_query = "UPDATE users SET verify_status = '1' WHERE verify_token = '$token' LIMIT 1";
            $update_query_run = mysqli_query($conn, $update_query);

            if ($update_query_run) {
                $_SESSION['status'] = "Your user account has been verified successfully!";
                header("Location: login.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Verification failed. Please try again.";
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Your user email is already verified. Please log in.";
            header("Location: login.php");
            exit(0);
        }
    } elseif (mysqli_num_rows($verify_query_run_doctor) > 0) {
        $row = mysqli_fetch_assoc($verify_query_run_doctor);

        if ($row['verify_status'] == "0") {
            // Update doctor verification status
            $update_query = "UPDATE doctors SET verify_status = '1' WHERE verify_token = '$token' LIMIT 1";
            $update_query_run = mysqli_query($conn, $update_query);

            if ($update_query_run) {
                $_SESSION['status'] = "Your doctor account has been verified successfully!";
                header("Location: login.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Verification failed. Please try again.";
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Your doctor email is already verified. Please log in.";
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Invalid or expired token.";
        header("Location: login.php");
        exit(0);
    }
} else {
    $_SESSION['status'] = "Access not allowed.";
    header("Location: login.php");
    exit(0);
}
?>