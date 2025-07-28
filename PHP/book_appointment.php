<?php
session_start();
include 'connect.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    die("User not logged in.");
}

// Get the logged-in user's ID
$UserID = $_SESSION['UserID'];
echo "UserID (from session): " . $UserID . "<br>";

if (isset($_POST['book_appointment'])) {
    // Get form data from the booking form
    $doctor_name = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_type = $_POST['appointment_type'];
    $status = 'Scheduled'; // Default status

    // Insert the new appointment into the database
    $query = "INSERT INTO appointments (UserID, doctor_name, specialization, appointment_date, appointment_time, appointment_type, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssss", $UserID, $doctor_name, $specialization, $appointment_date, $appointment_time, $appointment_type, $status);
    $stmt->execute();

    // After booking, redirect to the upcoming appointments page
    header("Location: upcoming_appointments.php");
    exit();
}
?>

<!-- Example booking form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
</head>

<body>

    <h2>Book an Appointment</h2>
    <form method="POST">
        <label for="doctor_name">Doctor Name:</label><br>
        <input type="text" name="doctor_name" required><br><br>

        <label for="specialization">Specialization:</label><br>
        <input type="text" name="specialization" required><br><br>

        <label for="appointment_date">Appointment Date:</label><br>
        <input type="date" name="appointment_date" required><br><br>

        <label for="appointment_time">Appointment Time:</label><br>
        <input type="time" name="appointment_time" required><br><br>

        <label for="appointment_type">Appointment Type:</label><br>
        <input type="text" name="appointment_type" required><br><br>

        <button type="submit" name="book_appointment">Book Appointment</button>
    </form>

</body>

</html>