<?php
session_start();
include('connect.php'); // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['UserID']) || empty($_SESSION['UserID'])) {
    die("User not logged in. Please log in first.");
}

// Validate session variables for fName and lName
if (!isset($_SESSION['fName'], $_SESSION['lName'])) {
    die("User details are missing. Please log in again.");
}

// Check if the DoctorID is passed in the form
if (!isset($_POST['doctor_id']) || empty($_POST['doctor_id'])) {
    die("Doctor not selected. Please select a doctor.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $doctor_id = intval($_POST['doctor_id']);  // Capture DoctorID from the form (ensure it's passed)
    $doctor_name = $conn->real_escape_string($_POST['doctor_name']);
    $specialization = $conn->real_escape_string($_POST['doctor-specialization']);
    $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
    $appointment_time = $conn->real_escape_string($_POST['appointment_time']);
    $appointment_status = 'pending'; // Default status is pending
    $appointment_type = $conn->real_escape_string($_POST['appointment_status']); // Online or face-to-face
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $country = $conn->real_escape_string($_POST['country']);
    $address = $conn->real_escape_string($_POST['address']);
    $UserID = intval($_SESSION['UserID']);
    $DoctorID = $doctor_id; // Use the selected DoctorID from the form
    $fName = $conn->real_escape_string($_SESSION['fName']);
    $lName = $conn->real_escape_string($_SESSION['lName']); 

    // Prepare SQL query to insert data into the appointments table
    $stmt = $conn->prepare("INSERT INTO appointments (DoctorID, doctor_name, specialization, appointment_date, appointment_time, appointment_status, appointment_type, email, phone_number, country, address, UserID, fName, lName) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssssiss", $DoctorID, $doctor_name, $specialization, $appointment_date, $appointment_time, $appointment_status, $appointment_type, $email, $phone_number, $country, $address, $UserID, $fName, $lName);

    // Execute the query
    if ($stmt->execute()) {
        echo "Appointment booked successfully! You will be notified after admin approval.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>