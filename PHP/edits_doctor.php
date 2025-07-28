<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch doctor details to edit
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

// Update doctor details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $conn->real_escape_string($_POST['fName']);
    $lastName = $conn->real_escape_string($_POST['lName']);
    $specialization = $conn->real_escape_string($_POST['specialization']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone_number']);

    $updateQuery = "UPDATE doctors SET fName = '$firstName', lName = '$lastName', specialization = '$specialization', email = '$email', phone_number = '$phone' WHERE DoctorID = $doctorID";

    if ($conn->query($updateQuery)) {
        echo "<p>Doctor updated successfully. <a href='admin_dashboard.php'>Go back to dashboard</a></p>";
    } else {
        echo "<p>Error updating doctor: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="css/edits_doctor_styles.css">
</head>

<body>
    <h1>Edit Doctor Details</h1>

    <form method="POST" action="">
        <label for="fName">First Name:</label><br>
        <input type="text" id="fName" name="fName" value="<?php echo htmlspecialchars($doctor['fName']); ?>"
            required><br><br>

        <label for="lName">Last Name:</label><br>
        <input type="text" id="lName" name="lName" value="<?php echo htmlspecialchars($doctor['lName']); ?>"
            required><br><br>

        <label for="specialization">Specialization:</label><br>
        <input type="text" id="specialization" name="specialization"
            value="<?php echo htmlspecialchars($doctor['specialization']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>"
            required><br><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number"
            value="<?php echo htmlspecialchars($doctor['phone_number']); ?>" required><br><br>

        <button type="submit">Update Doctor</button>
    </form>

    <a href="manage_doctors.php" class="back-link">Back to Dashboard</a>
</body>

</html>