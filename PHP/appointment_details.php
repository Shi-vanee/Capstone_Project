<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "website"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Retrieve the appointment details from the database
    $sql = "SELECT * FROM appointments WHERE appointment_id = '$appointment_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the appointment data
        $appointment = $result->fetch_assoc();
    } else {
        echo "Appointment not found.";
        exit();
    }
} else {
    // If no ID is provided in the URL
    echo "No appointment ID provided.";
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>

<body>
    <div class="container">
        <h2>Your Appointment Details</h2>
        <table class="table table-bordered">
            <tr>
                <th>Doctor's Name</th>
                <td><?php echo isset($appointment['doctor_name']) ? $appointment['doctor_name'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Specialization</th>
                <td><?php echo isset($appointment['specialization']) ? $appointment['specialization'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Appointment Date</th>
                <td><?php echo isset($appointment['appointment_date']) ? $appointment['appointment_date'] : 'N/A'; ?>
                </td>
            </tr>
            <tr>
                <th>Appointment Time</th>
                <td><?php echo isset($appointment['appointment_time']) ? $appointment['appointment_time'] : 'N/A'; ?>
                </td>
            </tr>
            <tr>
                <th>Appointment Type</th>
                <td><?php echo isset($appointment['appointment_type']) ? $appointment['appointment_type'] : 'N/A'; ?>
                </td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td><?php echo isset($appointment['email']) ? $appointment['email'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo isset($appointment['phone_number']) ? $appointment['phone_number'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo isset($appointment['country']) ? $appointment['country'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo isset($appointment['address']) ? $appointment['address'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo isset($appointment['status']) ? $appointment['status'] : 'N/A'; ?></td>
            </tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.js"></script>
</body>

</html>