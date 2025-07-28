<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
echo "<h1>Welcome, " . $_SESSION['admin_name'] . "</h1>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard_styles.css">
</head>

<body>
    <h1>Admin Dashboard</h1>

    <!-- Users Table -->
    <h2>Registered Users</h2>
    <table border="1">
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
            $users = $conn->query("SELECT * FROM users");
            while ($user = $users->fetch_assoc()) {
                echo "<tr>
                    <td>{$user['UserID']}</td>
                    <td>{$user['fName']} {$user['lName']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['phone_number']}</td>
                    <td>
                        <a href='edits_user.php?id={$user['UserID']}'>Edit</a> |
                        <a href='deletes_user.php?id={$user['UserID']}'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Doctors Table -->
    <h2>Registered Doctors</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $doctors = $conn->query("SELECT * FROM doctors");
            while ($doctor = $doctors->fetch_assoc()) {
                echo "<tr>
                    <td>{$doctor['DoctorID']}</td>
                    <td>{$doctor['fName']} {$doctor['lName']}</td>
                    <td>{$doctor['specialization']}</td>
                    <td>{$doctor['email']}</td>
                    <td>{$doctor['phone_number']}</td>
                    <td>
                        <a href='edits_doctor.php?id={$doctor['DoctorID']}'>Edit</a> |
                        <a href='deletes_doctor.php?id={$doctor['DoctorID']}'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Appointments Table -->
    <h2>User Appointments</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Appointment_ID</th>
                <th>User ID</th>
                <th>Doctor ID</th>
                <th>Doctor Name</th>
                <th>User Last Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointments = $conn->query("SELECT * FROM appointments");
            while ($appointment = $appointments->fetch_assoc()) {
                echo "<tr>
                    <td>{$appointment['appointment_id']}</td>
                    <td>{$appointment['UserID']}</td>
                    <td>{$appointment['DoctorID']}</td>
                    <td>{$appointment['doctor_name']}</td>
                    <td>{$appointment['lName']}</td>
                    <td>{$appointment['appointment_date']}</td>
                    <td>{$appointment['appointment_time']}</td>
                    <td>{$appointment['appointment_type']}</td>
                    <td>
                        <a href='update_appointment.php?id={$appointment['appointment_id']}&status=confirmed'>Confirm</a> |
                        <a href='update_appointment.php?id={$appointment['appointment_id']}&status=rejected'>Reject</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Logout Button -->
    <a href="admin_logout.php" class="logout-button">Logout</a>

</body>

</html>