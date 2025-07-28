<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .navbar-brand {
        font-weight: bold;
        color: white !important;
    }

    @media(min-width:768px) {
        body {
            margin-top: 50px;
        }
    }

    #wrapper {
        padding-left: 0;
    }

    #page-wrapper {
        width: 100%;
        padding: 0;
        background-color: #fff;
    }

    @media(min-width:768px) {
        #wrapper {
            padding-left: 225px;
        }

        #page-wrapper {
            padding: 22px 10px;
        }
    }

    /* Top Navigation */
    .navbar-inverse {
        background-color: #e46713 !important;
        border-color: rgb(255, 255, 255);
    }

    .navbar-inverse .navbar-nav>.open>a,
    .navbar-inverse .navbar-nav>.open>a:hover,
    .navbar-inverse .navbar-nav>.open>a:focus {
        background-color: #e46713 !important;
    }

    .navbar-inverse .navbar-collapse,
    .navbar-inverse .navbar-form {
        border-color: #e46713 !important;
        background-color: #e46713 !important;
    }

    .navbar-inverse .navbar-toggle {
        border-color: white !important;
    }

    .navbar-inverse .navbar-toggle:hover,
    .navbar-inverse .navbar-toggle:focus {
        background-color: #cf5d0e !important;
    }

    .top-nav {
        padding: 0 15px;
    }

    .top-nav>li {
        display: inline-block;
        float: none;
    }

    .top-nav>li>a {
        padding-top: 20px;
        padding-bottom: 20px;
        line-height: 20px;
        color: white;
        font-weight: 600;
    }

    /* Side Navigation */
    @media(min-width:768px) {
        .side-nav {
            position: fixed;
            top: 60px;
            left: 225px;
            width: 225px;
            margin-left: -225px;
            border: none;
            border-radius: 0;
            border-top: 1px solid #cf5d0e;
            overflow-y: auto;
            background-color: #e46713 !important;
            bottom: 0;
            overflow-x: hidden;
            padding-bottom: 40px;
        }

        .side-nav>li>a {
            width: 225px;
            background-color: #cf5d0e !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff !important;
            font-weight: 600;
        }

        .side-nav>li>a:hover,
        .side-nav>li>a:focus {
            outline: none;
            background-color: #cf5d0e !important;
            color: white !important;
        }

        .side-nav>.active>a {
            background-color: #cf5d0e !important;
            border-left: 4px solid white;
            color: white !important;
        }

        .side-nav li a,
        .side-nav li a i {
            color: white !important;
        }
    }

    /* Main Content */
    #main h1 {
        font-weight: 700;
        color: #e46713;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e46713;
    }

    .well {
        background: white;
        border: 1px solid #e46713;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        border-radius: 4px;
        margin-bottom: 30px;
    }

    .well h2 {
        font-weight: 700;
        color: #e46713;
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #e46713;
    }

    /* Tables */
    .table-responsive {
        margin: 20px 0;
    }

    table {
        background: white;
        border: 1px solid #e46713;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table>thead>tr>th {
        background-color: #e46713;
        color: white;
        border-bottom: none;
        font-weight: 600;
    }

    /* Buttons */
    .btn-xs {
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 3px;
        margin: 2px;
    }

    .btn-primary {
        background-color: #e46713;
        border-color: #e46713;
    }

    .btn-primary:hover {
        background-color: #cf5d0e;
        border-color: #cf5d0e;
    }

    .btn-danger {
        background-color: #d9534f;
        border-color: #d43f3a;
    }

    .btn-success {
        background-color: #5cb85c;
        border-color: #4cae4c;
    }

    .btn-warning {
        background-color: #f0ad4e;
        border-color: #eea236;
    }

    /* Return button styling */
    .return-btn {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="admin_dashboard_test.php">
                    <span>Admin Dashboard</span>
                </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i> <?php echo $_SESSION['admin_name']; ?> <b
                            class="fa fa-angle-down"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="admin_logout.php"><i class="fa fa-fw fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="admin_dashboard_test.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li class="active">
                        <a href="manage_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a>
                    </li>
                    <li>
                        <a href="manage_doctors.php"><i class="fa fa-fw fa-user-md"></i> Manage Doctors</a>
                    </li>
                    <li>
                        <a href="manage_appointments.php"><i class="fa fa-fw fa-calendar"></i> Manage Appointments</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row" id="main">
                    <div class="col-sm-12 col-md-12">
                        <!-- Return Button Added Here -->
                        <a href="admin_dashboard_test.php" class="btn btn-primary return-btn">
                            <i class="fa fa-arrow-left"></i> Return to Dashboard
                        </a>

                        <h1>Manage Users</h1>

                        <!-- Users Table -->
                        <div class="well">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $users = $conn->query("SELECT * FROM users ORDER BY UserID DESC");
                                        while ($user = $users->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$user['UserID']}</td>
                                                <td>{$user['fName']}</td>
                                                <td>{$user['lName']}</td>
                                                <td>{$user['email']}</td>
                                                <td>{$user['phone_number']}</td>
                                                <td>
                                                    <a href='edits_user.php?id={$user['UserID']}' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> Edit</a>
                                                    <a href='delete_user.php?id={$user['UserID']}' class='btn btn-danger btn-xs' onclick='return confirm(\"Are you sure you want to delete this user?\")'><i class='fa fa-trash'></i> Delete</a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</body>

</html>