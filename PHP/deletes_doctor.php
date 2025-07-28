<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch doctor details to confirm deletion
if (isset($_GET['id'])) {
    $doctorID = intval($_GET['id']);
    $doctorQuery = $conn->query("SELECT * FROM doctors WHERE DoctorID = $doctorID");

    if ($doctorQuery->num_rows > 0) {
        $doctor = $doctorQuery->fetch_assoc();
    } else {
        echo "Doctor not found.";
        exit();
    }
} else {
    echo "Invalid doctor ID.";
    exit();
}

// Delete doctor from database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deleteQuery = "DELETE FROM doctors WHERE DoctorID = $doctorID";

    if ($conn->query($deleteQuery)) {
        echo "<p>Doctor deleted successfully. <a href='admin_dashboard.php'>Go back to dashboard</a></p>";
    } else {
        echo "<p>Error deleting doctor: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Doctor</title>
    <link rel="stylesheet" href="css/deletes_doctor_styles.css"> <!-- Add your CSS file here -->
</head>

<body>
    <h1>Delete Doctor</h1>

    <p>Are you sure you want to delete the following doctor?</p>

    <table>
        <tr>
            <th>First Name</th>
            <td><?php echo htmlspecialchars($doctor['fName']); ?></td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td><?php echo htmlspecialchars($doctor['lName']); ?></td>
        </tr>
        <tr>
            <th>Specialization</th>
            <td><?php echo htmlspecialchars($doctor['specialization']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($doctor['email']); ?></td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td><?php echo htmlspecialchars($doctor['phone_number']); ?></td>
        </tr>
    </table>

    <!-- Confirmation Form -->
    <form method="POST" action="">
        <button type="submit" class="delete-button">Delete Doctor</button>
        <a href="admin_dashboard.php" class="back-link">Cancel</a>
    </form>
</body>

</html>