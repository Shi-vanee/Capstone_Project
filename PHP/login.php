<?php include('session_handler.php'); ?>

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

    <title>Trust MediCare</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <!-- nice select -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
        integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <!-- datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <style>
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

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
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

    <div class="hero_area">
        <!-- header section -->
        <header class="header_section">
            <div class="header_top">
                <div class="container">
                    <div class="social_box">
                        <a href="">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="header_bottom">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="navbar-brand" href="index.php">
                            <img src="images/logo.jpg.jpg" alt="">
                        </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                                <ul class="navbar-nav  ">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="index.php">Home <span
                                                class="sr-only">(current)</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="about us.html"> About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="resources.html">Resources</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="quote_btn-container">
                                <a href="login.html">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>
                                        Login
                                    </span>
                                </a>
                                <a href="appointment.html">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>
                                        Profile
                                    </span>
                                </a>
                                <form class="form-inline">
                                    <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
    </div>
    <!-- end header section -->

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

            <h1 class="form-title">Register</h1>

            <form method="POST" action="register.php" onsubmit="return validateRegistration()">
                <input type="hidden" name="action" value="register">

                <div class="input-group">
                    <label for="register-role">Register as:</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="" disabled selected>Choose Role</option>
                        <option value="user">User</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </div>

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

                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password"
                        required>
                    <label for="confirm-password">Confirm Password</label>
                </div>

                <!-- Specialization Field -->
                <div class="input-group" id="register-specialization-group" style="display: none;">
                    <label for="register-specialization">Specialization:</label>
                    <input type="text" name="specialization" id="register-specialization"
                        placeholder="Enter specialization">
                </div>

                <input type="submit" class="btn" value="Sign Up" name="signUp">
            </form>
            <div class="icons">
                <i class="fa fa-google"></i>
                <i class="fa fa-facebook"></i>
            </div>
            <div class="links">
                <p>Already Have Account ?</p>
                <button id="signInButton">Sign In</button>
            </div>
        </div>

        <div class="container" id="signIn">
            <h1 class="form-title">Login</h1>

            <?php
            if (isset($_SESSION['login_status'])) {
                $alertClass = strpos($_SESSION['login_status'], 'successfully') !== false ? 'alert-success' : 'alert-danger';
                ?>
            <div class="alert <?php echo $alertClass; ?>">
                <?= $_SESSION['login_status']; ?>
            </div>
            <?php
                unset($_SESSION['login_status']);
            }
            ?>

            <form method="POST" action="signin.php" onsubmit="return validateLogin()">
                <input type="hidden" name="action" value="login">

                <div class="input-group">
                    <label for="login-role">Login as:</label>
                    <select class="form-control" id="login-role" name="role" required>
                        <option value="" disabled selected>Choose Role</option>
                        <option value="user">User</option>
                        <option value="doctor">Doctor</option>
                    </select>
                </div>

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
                    <a href="forgot_password.php">Recover Password</a>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Registration Role Dropdown
        const registerRole = document.getElementById('role');
        const registerSpecializationGroup = document.getElementById('register-specialization-group');

        if (registerRole) {
            registerRole.addEventListener('change', function() {
                if (this.value === 'doctor') {
                    registerSpecializationGroup.style.display = 'block';
                } else {
                    registerSpecializationGroup.style.display = 'none';
                }
            });
        }

        // Toggle between sign in and sign up forms
        const signInButton = document.getElementById('signInButton');
        const signUpButton = document.getElementById('signUpButton');
        const signInForm = document.getElementById('signIn');
        const signUpForm = document.getElementById('signup');

        if (signInButton && signUpButton) {
            signInButton.addEventListener('click', function() {
                signUpForm.style.display = 'none';
                signInForm.style.display = 'block';
            });

            signUpButton.addEventListener('click', function() {
                signInForm.style.display = 'none';
                signUpForm.style.display = 'block';
            });
        }

        // Check URL for form parameter
        const urlParams = new URLSearchParams(window.location.search);
        const formParam = urlParams.get('form');

        if (formParam === 'signup') {
            signInForm.style.display = 'none';
            signUpForm.style.display = 'block';
        }

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

    function validateRegistration() {
        const password = document.getElementById('register-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // Check password requirements
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
        if (!regex.test(password)) {
            alert(
                'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            );
            return false;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return false;
        }

        return true;
    }

    function validateLogin() {
        const password = document.getElementById('login-password').value;

        // Check password requirements
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
        if (!regex.test(password)) {
            alert(
                'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            );
            return false;
        }

        return true;
    }
    </script>

    <!-- info section -->
    <section class="info_section ">
        <div class="container">
            <div class="info_top">
                <div class="info_logo">
                </div>
            </div>
            <div class="info_bottom layout_padding2">
                <div class="row info_main_row">
                    <div class="col-md-6 col-lg-3">
                        <h5>
                            Address
                        </h5>
                        <div class="info_contact">
                            <a href="">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>
                                    Location
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>
                                    Call +230 51234567
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope"></i>
                                <span>
                                    trustmedicare@gmail.com
                                </span>
                            </a>
                        </div>
                        <div class="social_box">
                            <a href="">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info_links">
                            <h5>
                                Resources
                            </h5>
                            <div class="info_links_menu">
                                <a class="active" href="about us.html">
                                    About Us
                                </a>
                                <a href="resources.html">
                                    Resources
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info_post">
                            <h5>
                                Latest news
                            </h5>
                            <p>
                                <a href="https://www.bbc.com/news/articles/c4g519dz1d7o">
                                    Crafting is 'very important to my mental health'
                                </a>
                            </p>
                        </div>
                        <p>
                            <a href="https://www.bbc.com/news/articles/cdrj7v5z725o">
                                Heartstopper: 'How Netflix show's eating disorder story helps me'
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end info_section -->

    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By Trust MediCare
            </p>
        </div>
    </footer>
    <!-- footer section -->

    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <!-- nice select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"
        integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <!-- owl slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</body>

</html>