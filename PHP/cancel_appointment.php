<?php
// Start session to access UserID
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    die("User not logged in.");
}

include 'connect.php';

// Check if the form is submitted to cancel the appointment
if (isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $UserID = $_SESSION['UserID'];

    // Delete the appointment from the database
    $query = "DELETE FROM appointments WHERE appointment_id = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $UserID);

    if ($stmt->execute()) {
        echo "Appointment canceled successfully!";
        header("Location: upcoming_appointments.php"); // Redirect back to upcoming appointments page
        exit();
    } else {
        echo "Error: Could not cancel appointment.";
    }
}
?>