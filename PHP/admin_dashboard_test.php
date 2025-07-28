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
    <title>Admin Dashboard</title>
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

    /* Cards */
    .dashboard-card {
        border: 1px solid #e46713;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #e46713;
        margin-bottom: 15px;
    }

    .card-count {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .card-link {
        color: #e46713;
        font-weight: 600;
        text-decoration: none;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    .card-icon {
        font-size: 50px;
        color: #e46713;
        margin-bottom: 15px;
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

    /* Recent items table */
    .recent-table {
        font-size: 14px;
    }

    .recent-table th {
        background-color: #f5f5f5;
        color: #333;
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
                    <li class="active">
                        <a href="admin_dashboard_test.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
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
                        <h1>Welcome, <?php echo $_SESSION['admin_name']; ?></h1>

                        <!-- Quick Stats Row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="dashboard-card text-center">
                                    <div class="card-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <div class="card-title">Total Users</div>
                                    <div class="card-count">
                                        <?php 
                                        $user_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc();
                                        echo $user_count['count'];
                                        ?>
                                    </div>
                                    <a href="manage_users.php" class="card-link">View All Users <i
                                            class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="dashboard-card text-center">
                                    <div class="card-icon">
                                        <i class="fa fa-user-md"></i>
                                    </div>
                                    <div class="card-title">Total Doctors</div>
                                    <div class="card-count">
                                        <?php 
                                        $doctor_count = $conn->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc();
                                        echo $doctor_count['count'];
                                        ?>
                                    </div>
                                    <a href="manage_doctors.php" class="card-link">View All Doctors <i
                                            class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="dashboard-card text-center">
                                    <div class="card-icon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="card-title">Total Appointments</div>
                                    <div class="card-count">
                                        <?php 
                                        $appointment_count = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc();
                                        echo $appointment_count['count'];
                                        ?>
                                    </div>
                                    <a href="manage_appointments.php" class="card-link">View All Appointments <i
                                            class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Users -->
                        <div class="well">
                            <h2><i class="fa fa-users"></i> Recent Users</h2>
                            <div class="table-responsive">
                                <table class="table table-striped recent-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $users = $conn->query("SELECT * FROM users ORDER BY UserID DESC LIMIT 5");
                                        while ($user = $users->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$user['UserID']}</td>
                                                <td>{$user['fName']} {$user['lName']}</td>
                                                <td>{$user['email']}</td>
                                                <td>{$user['phone_number']}</td>
                                                <td>
                                                    <a href='manage_users.php' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i> View</a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <a href="manage_users.php" class="btn btn-primary">View All Users</a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Doctors -->
                        <div class="well">
                            <h2><i class="fa fa-user-md"></i> Recent Doctors</h2>
                            <div class="table-responsive">
                                <table class="table table-striped recent-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Specialization</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $doctors = $conn->query("SELECT * FROM doctors ORDER BY DoctorID DESC LIMIT 5");
                                        while ($doctor = $doctors->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$doctor['DoctorID']}</td>
                                                <td>{$doctor['fName']} {$doctor['lName']}</td>
                                                <td>{$doctor['specialization']}</td>
                                                <td>{$doctor['email']}</td>
                                                <td>
                                                    <a href='manage_doctors.php' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i> View</a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <a href="manage_doctors.php" class="btn btn-primary">View All Doctors</a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Appointments -->
                        <div class="well">
                            <h2><i class="fa fa-calendar"></i> Recent Appointments</h2>
                            <div class="table-responsive">
                                <table class="table table-striped recent-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $appointments = $conn->query("SELECT * FROM appointments ORDER BY appointment_id DESC LIMIT 5");
                                        while ($appointment = $appointments->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$appointment['appointment_id']}</td>
                                                <td>{$appointment['lName']}</td>
                                                <td>{$appointment['doctor_name']}</td>
                                                <td>{$appointment['appointment_date']}</td>
                                                <td>{$appointment['appointment_time']}</td>
                                                <td>{$appointment['appointment_type']}</td>
                                                <td>
                                                    <a href='manage_appointments.php' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i> View</a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <a href="manage_appointments.php" class="btn btn-primary">View All Appointments</a>
                                </div>
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