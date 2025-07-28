<?php
session_start(); // Start the session at the very beginning

// Ensure the role is set in the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Determine the dashboard link based on the user's role
if ($role === 'doctor') {
    $dashboardLink = "dashboard.php";
} elseif ($role === 'user') {
    $dashboardLink = "dashboard.php";
} else {
    $dashboardLink = "login.php"; // Default to login if no role is found
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


                            <!-- Login or Profile Dropdown -->
                            <!-- Profile Dropdown -->
                            <div class="quote_btn-container">
                                <?php if ($role): ?>
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="#" role="button" id="profileDropdown"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span>Profile</span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                                        <a class="dropdown-item" href="<?php echo $dashboardLink; ?>">Edit Profile</a>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </div>
                                </div>
                                <?php else: ?>
                                <a href="login.php">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>Login</span>
                                </a>
                                <?php endif; ?>



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

        <!-- end header section -->


        <!-- slider section -->
        <section class="slider_section ">
            <div class="dot_design">
                <img src="images/dots.png" alt="">
            </div>
            <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-box">
                                        <div class="play_btn">
                                            <button>
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <h1>
                                            Trust <br>
                                            <span>
                                                MediCare
                                            </span>
                                        </h1>
                                        <p>
                                            <b>With trusted care for wellness, everywhere</b>
                                        </p>
                                        <a href="login.php">
                                            Get Started
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="img-box">
                                        <img src="images/slider-img.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="container ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-box">
                                        <div class="play_btn">
                                            <button>
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <h1>
                                            Trust <br>
                                            <span>
                                                MediCare
                                            </span>
                                        </h1>
                                        <p>
                                            <b>With trusted care for wellness, everywhere</b>
                                        </p>
                                        <a href="login.html">
                                            Get Started
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="img-box">
                                        <img src="images/slider-img.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="container ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-box">
                                        <div class="play_btn">
                                            <button>
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <h1>
                                            Trust <br>
                                            <span>
                                                MediCare
                                            </span>
                                        </h1>
                                        <p>
                                            <b>With trusted care for wellness, everywhere</b>
                                        </p>
                                        <a href="login.html">
                                            Get Started
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="img-box">
                                        <img src="images/slider-img.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->


        <!-- about section -->

        <section class="about_section">
            <div class="container  ">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="img-box">
                            <img src="images/about-img.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box">
                            <div class="heading_container">
                                <h2>
                                    Our <span>Mission</span>
                                </h2>
                            </div>
                            <p>
                                Understanding our minds is the first step toward a healthier us. Together, let’s embrace
                                the
                                journey of self-discovery and empower each other to cultivate well-being
                            </p>
                            <a href="about us.html">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- end about section -->


        <!-- Resources section -->

        <section class="resource_section layout_padding">
            <div class="side_img">

            </div>
            <div class="container">
                <div class="heading_container heading_center">
                    <h2>
                        <span>Resources</span>
                    </h2>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/t1.png" alt="Centered image/t1">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Mental Disorder
                                </h4>
                                <a href="mental_disorder.html">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/t1.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Neurological & Genetic Disorders
                                </h4>
                                <a href="neurological.html">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/t1.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Therapy Informations
                                </h4>
                                <a href="therapy_info.html">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="box ">
                            <div class="img-box">
                                <img src="images/t1.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Health & Wellness
                                </h4>
                                <a href="health_wellness.html">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- end resources section -->


        <!-- info section -->
        <section class="info_section ">
            <div class="container">
                <div class="info_top">
                    <div class="info_logo">
                        <a href="">
                            <img src="images/logo.jpg.jpg" alt="">
                        </a>
                    </div>
                    <div class="info_form">
                        <form action="">
                            <input type="email" placeholder="Your email">
                            <button>
                                Send
                            </button>
                        </form>
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