<?php 
include('session_handler.php');
include('connect.php');

// Handle registration form submission
if (isset($_POST['signUp'])) {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password_hash'], PASSWORD_BCRYPT);
    $name = $fName . ' ' . $lName;

    // Check if the email is already registered
    $checkQuery = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $registerError = "An account with this email already exists.";
    } else {
        // Insert new admin into the database
        $insertQuery = $conn->prepare("INSERT INTO admin (name, email, password) VALUES (?, ?, ?)");
        $insertQuery->bind_param("sss", $name, $email, $password);
        if ($insertQuery->execute()) {
            $_SESSION['status'] = "Registration successful. You can now log in.";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $registerError = "Error: Unable to register. Please try again.";
        }
    }
}

// Handle login form submission
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query to check admin credentials
    $query = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            // Set session variables for the admin
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard_test.php");
            exit();
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "No admin account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Admin Login</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <style>
    body {
        font-family: 'poppins', sans-serif;
        color: #0c0c0c;
        background-color: #ffffff;
        overflow-x: hidden;
    }

    .logo-container {
        text-align: center;
        margin: 20px 0;
    }

    .logo-container h1 {
        color: #c64f00;
        font-size: 2.5rem;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .login_section {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        background-color: #ffffff;
        background: linear-gradient(to right, #ffffff, #ffffff);
    }

    .login_section .container {
        background: #fff;
        width: 500px;
        padding: 1.5rem;
        margin: 20px auto;
        border-radius: 10px;
        box-shadow: 0 20px 35px rgba(0, 0, 1, 0.9);
    }

    .login_section form {
        margin: 0 2rem;
    }

    .login_section .form-title {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        padding: 1.3rem;
        margin-bottom: 0.4rem;
        color: #333;
    }

    .login_section .input-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .login_section input {
        color: inherit;
        width: 100%;
        background-color: transparent;
        border: none;
        border-bottom: 1px solid #ccc;
        padding-left: 1.5rem;
        font-size: 15px;
        height: 40px;
    }

    .login_section .input-group i {
        position: absolute;
        color: #c64f00;
        left: 0;
        top: 12px;
    }

    .login_section input:focus {
        background-color: transparent;
        outline: transparent;
        border-bottom: 2px solid #c64f00;
    }

    .login_section input::placeholder {
        color: transparent;
    }

    .login_section label {
        color: #666;
        position: relative;
        left: 1.2em;
        top: -1.3em;
        cursor: auto;
        transition: 0.3s ease all;
    }

    .login_section input:focus~label,
    input:not(:placeholder-shown)~label {
        top: -3em;
        color: #c64f00;
        font-size: 15px;
    }

    .login_section .recover {
        text-align: right;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .login_section .recover a {
        text-decoration: none;
        color: #c64f00;
    }

    .login_section .recover a:hover {
        color: #c64f00;
        text-decoration: underline;
    }

    .login_section .btn {
        font-size: 1.1rem;
        padding: 8px 0;
        border-radius: 5px;
        outline: none;
        border: none;
        width: 100%;
        background: #c64f00;
        color: white;
        cursor: pointer;
        transition: 0.9s;
    }

    .login_section .btn:hover {
        background: #07001f;
    }

    .login_section .or {
        font-size: 1.1rem;
        margin-top: 0.5rem;
        text-align: center;
    }

    .login_section .icons {
        text-align: center;
        margin: 15px 0;
    }

    .login_section .icons i {
        color: #c64f00;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-size: 1.5rem;
        cursor: pointer;
        border: 2px solid #f0f0f0;
        margin: 0 15px;
        transition: 1s;
    }

    .login_section .icons i:hover {
        background: #07001f;
        font-size: 1.6rem;
        border: 2px solid #c64f00;
    }

    .login_section .links {
        display: flex;
        justify-content: space-around;
        padding: 0 4rem;
        margin-top: 0.9rem;
        font-weight: bold;
    }

    .login_section button {
        color: #c64f00;
        border: none;
        background-color: transparent;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
    }

    .login_section button:hover {
        text-decoration: underline;
        color: #c64f00;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }

    .password-hint {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        padding-left: 20px;
    }

    .password-hint ul {
        margin: 5px 0;
        padding-left: 20px;
    }

    .password-hint li {
        list-style-type: disc;
    }

    .invalid-password {
        border-color: #ff0000 !important;
    }
    </style>
</head>

<body>
    <div class="logo-container">
        <h1>TrustMedicare</h1>
    </div>

    <!-- login section -->
    <section class="login_section">
        <div class="container" id="signup" style="display:none;">
            <?php
            if (isset($_SESSION['status'])) {
                $alertClass = strpos($_SESSION['status'], 'successfully') !== false ? 'alert-success' : 'alert-danger';
                ?>
            <div class="alert <?php echo $alertClass; ?>">
                <?= $_SESSION['status']; ?>
            </div>
            <?php
                unset($_SESSION['status']);
            }
            ?>

            <?php if (isset($registerError)): ?>
            <div class="alert alert-danger">
                <?= $registerError; ?>
            </div>
            <?php endif; ?>

            <h1 class="form-title">Admin Register</h1>

            <form method="POST">
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" name="fName" id="fName" placeholder="First Name" required>
                    <label for="fname">First Name</label>
                </div>
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                    <label for="lName">Last Name</label>
                </div>
                <div class="input-group">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" id="register-email" placeholder="Email" required>
                    <label for="register-email">Email</label>
                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password_hash" id="register-password" placeholder="Password" required
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                        title="Must contain at least 8 characters, including uppercase, lowercase, number, and special character">
                    <label for="register-password">Password</label>
                    <div class="password-hint">
                    </div>
                </div>

                <input type="submit" class="btn" value="Sign Up" name="signUp">
            </form>
            <div class="icons">
                <i class="fa fa-google"></i>
                <i class="fa fa-facebook"></i>
            </div>
            <div class="links">
                <p>Already Have Account?</p>
                <button id="signInButton">Sign In</button>
            </div>
        </div>

        <div class="container" id="signIn">
            <?php if (isset($loginError)): ?>
            <div class="alert alert-danger">
                <?= $loginError; ?>
            </div>
            <?php endif; ?>

            <h1 class="form-title">Admin Login</h1>

            <form method="POST">
                <div class="input-group">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" id="login-email" placeholder="Email" required>
                    <label for="login-email">Email</label>
                </div>

                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="login-password" placeholder="Password" required
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                        title="Must contain at least 8 characters, including uppercase, lowercase, number, and special character">
                    <label for="login-password">Password</label>
                    <div class="password-hint">
                    </div>
                </div>
                <p class="recover">
                    <a href="#">Recover Password</a>
                </p>
                <input type="submit" class="btn" value="Sign In" name="signIn">
            </form>
            <div class="icons">
                <i class="fa fa-google"></i>
                <i class="fa fa-facebook"></i>
            </div>
            <div class="links">
                <p>Don't have account yet?</p>
                <button id="signUpButton">Sign Up</button>
            </div>
        </div>
    </section>

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const signUpButton = document.getElementById('signUpButton');
        const signInButton = document.getElementById('signInButton');
        const signUpForm = document.getElementById('signup');
        const signInForm = document.getElementById('signIn');

        signUpButton.addEventListener('click', function() {
            signInForm.style.display = 'none';
            signUpForm.style.display = 'block';
        });

        signInButton.addEventListener('click', function() {
            signUpForm.style.display = 'none';
            signInForm.style.display = 'block';
        });

        // Show appropriate form based on errors
        <?php if (isset($registerError) || isset($_SESSION['status'])): ?>
        signInForm.style.display = 'none';
        signUpForm.style.display = 'block';
        <?php elseif (isset($loginError)): ?>
        signUpForm.style.display = 'none';
        signInForm.style.display = 'block';
        <?php endif; ?>

        // Real-time password validation for registration
        const regPasswordInput = document.getElementById('register-password');
        if (regPasswordInput) {
            regPasswordInput.addEventListener('input', function() {
                validatePassword(this);
            });
        }

        // Real-time password validation for login
        const loginPasswordInput = document.getElementById('login-password');
        if (loginPasswordInput) {
            loginPasswordInput.addEventListener('input', function() {
                validatePassword(this);
            });
        }
    });

    function validatePassword(input) {
        const password = input.value;
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (password.length > 0 && !regex.test(password)) {
            input.classList.add('invalid-password');
        } else {
            input.classList.remove('invalid-password');
        }
    }
    </script>
</body>

</html>