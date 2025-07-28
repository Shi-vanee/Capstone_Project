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
    <title>Manage Appointments - Admin Dashboard</title>
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

    .status-pending {
        color: #f0ad4e;
        font-weight: bold;
    }

    .status-confirmed {
        color: #5cb85c;
        font-weight: bold;
    }

    .status-cancelled {
        color: #d9534f;
        font-weight: bold;
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
                <a class="navbar-brand" href="admin_dashboard.php">
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
                        <a href="admin_dashboard.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="manage_users.php"><i class="fa fa-fw fa-users"></i> Manage Users</a>
                    </li>
                    <li>
                        <a href="manage_doctors.php"><i class="fa fa-fw fa-user-md"></i> Manage Doctors</a>
                    </li>
                    <li class="active">
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
                        <h1>Manage Appointments</h1>

                        <!-- Filter Options -->
                        <div class="well" style="padding: 15px 20px;">
                            <form method="GET" class="form-inline">
                                <div class="form-group">
                                    <label for="status">Filter by Status:</label>
                                    <select name="status" id="status" class="form-control" style="margin-left: 10px;">
                                        <option value="">All Appointments</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left: 20px;">
                                    <label for="date">Filter by Date:</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        style="margin-left: 10px;">
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-left: 20px;">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="manage_appointments.php" class="btn btn-default" style="margin-left: 10px;">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </form>
                        </div>

                        <!-- Appointments Table -->
                        <div class="well">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Build the base query
                                        $query = "SELECT 
                                                    a.appointment_id,
                                                    a.fName as patient_fName,
                                                    a.lName as patient_lName,
                                                    a.doctor_name,
                                                    a.specialization,
                                                    a.appointment_date,
                                                    a.appointment_time,
                                                    a.appointment_type,
                                                    a.status
                                                  FROM appointments a
                                                  WHERE 1=1";
                                        
                                        // Add filters if they exist
                                        if (isset($_GET['status']) && !empty($_GET['status'])) {
                                            $query .= " AND a.status = '" . $conn->real_escape_string($_GET['status']) . "'";
                                        }
                                        
                                        if (isset($_GET['date']) && !empty($_GET['date'])) {
                                            $query .= " AND a.appointment_date = '" . $conn->real_escape_string($_GET['date']) . "'";
                                        }
                                        
                                        $query .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
                                        
                                        $result = $conn->query($query);
                                        
                                        if ($result && $result->num_rows > 0) {
                                            while ($appointment = $result->fetch_assoc()) {
                                                // Determine status class for styling
                                                $statusClass = 'status-' . $appointment['status'];
                                                
                                                echo "<tr>
                                                    <td>{$appointment['appointment_id']}</td>
                                                    <td>{$appointment['patient_fName']} {$appointment['patient_lName']}</td>
                                                    <td>{$appointment['doctor_name']} ({$appointment['specialization']})</td>
                                                    <td>{$appointment['appointment_date']}</td>
                                                    <td>{$appointment['appointment_time']}</td>
                                                    <td>{$appointment['appointment_type']}</td>
                                                    <td class='{$statusClass}'>" . ucfirst($appointment['status']) . "</td>
                                                    <td>
                                                        <a href='view_appointment.php?id={$appointment['appointment_id']}' class='btn btn-primary btn-xs'><i class='fa fa-eye'></i> View</a>
                                                        <a href='update_appointment.php?id={$appointment['appointment_id']}&status=confirmed' class='btn btn-success btn-xs'><i class='fa fa-check'></i> Confirm</a>
                                                        <a href='update_appointment.php?id={$appointment['appointment_id']}&status=cancelled' class='btn btn-danger btn-xs'><i class='fa fa-times'></i> Cancel</a>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No appointments found</td></tr>";
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
    <script>
    // Set the selected filter values if they exist
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const date = urlParams.get('date');

        if (status) {
            $('#status').val(status);
        }

        if (date) {
            $('#date').val(date);
        }
    });
    </script>
</body>

</html>