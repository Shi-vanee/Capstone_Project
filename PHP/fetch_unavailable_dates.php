<?php
include('connect.php');  // This includes your connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = intval($_POST['doctor_id']);  // Get doctor_id from the POST data

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT appointment_date FROM date_bookings WHERE doctor_id = ? AND booking_count >= 4");
    $stmt->bind_param('i', $doctor_id);  // Bind the doctor_id as an integer parameter
    $stmt->execute();  // Execute the query

    // Get the result from the executed query
    $result = $stmt->get_result();
    $unavailableDates = [];

    // Fetch all the rows and store the appointment dates in an array
    while ($row = $result->fetch_assoc()) {
        $unavailableDates[] = $row['appointment_date'];
    }

    // Output the unavailable dates as a JSON response
    echo json_encode($unavailableDates);
}
?>