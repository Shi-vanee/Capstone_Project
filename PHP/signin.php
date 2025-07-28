<?php
session_start();
include('connect.php');

if (isset($_POST['signIn'])) {
    // Collect and sanitize input values
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate fields
    if (!empty($email) && !empty($password) && !empty($role)) {
        // Determine the table based on the role
        if ($role === 'user') {
            $login_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        } elseif ($role === 'doctor') {
            $login_query = "SELECT * FROM doctors WHERE email='$email' LIMIT 1";
        } else {
            $_SESSION['status'] = "Invalid role selected.";
            header("Location: login.php");
            exit(0);
        }

        // Execute the login query
        $login_query_run = mysqli_query($conn, $login_query);

        if ($login_query_run) {
            if (mysqli_num_rows($login_query_run) > 0) {
                $row = mysqli_fetch_assoc($login_query_run);

                // Check password
                if (password_verify($password, $row['password_hash'])) {
                    // Check email verification status
                    if (isset($row['verify_status']) && $row['verify_status'] == "1") {
                        // Set session data for authenticated user
                        $_SESSION['authenticated'] = true;
                        $_SESSION['role'] = $role;

                        if ($role === 'user') {
                            $_SESSION['UserID'] = $row['UserID'];
                            $_SESSION['auth_user'] = [
                                'fName' => $row['fName'],
                                'lName' => $row['lName'],
                                'email' => $row['email']
                            ];
                        } elseif ($role === 'doctor') {
                            $_SESSION['DoctorID'] = $row['DoctorID'];
                            $_SESSION['auth_user'] = [
                                'fName' => $row['fName'],
                                'lName' => $row['lName'],
                                'email' => $row['email']
                            ];
                        }

                        // Set session for activity tracking
                        $_SESSION['last_activity'] = time();
                        $_SESSION['status'] = "You are logged in successfully.";
                        header("Location: dashboard.php");
                        exit(0);
                    } else {
                        // Email not verified
                        $_SESSION['status'] = "Your email is not verified. Please check your email for the OTP.";
                        header("Location: login.php");
                        exit(0);
                    }
                } else {
                    // Incorrect password
                    $_SESSION['status'] = "Invalid email or password.";
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                // Email not found
                $_SESSION['status'] = "Invalid email or password.";
                header("Location: login.php");
                exit(0);
            }
        } else {
            // Database query failed
            $_SESSION['status'] = "Database query failed: " . mysqli_error($conn);
            header("Location: login.php");
            exit(0);
        }
    } else {
        // Missing fields
        $_SESSION['status'] = "All fields are mandatory.";
        header("Location: login.php");
        exit(0);
    }
} else {
    $_SESSION['status'] = "Invalid access.";
    header("Location: login.php");
    exit(0);
}
?>