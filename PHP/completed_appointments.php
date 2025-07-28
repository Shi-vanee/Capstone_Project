<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$UserID = $_SESSION['UserID'];
$today = date("Y-m-d");

// Fetch completed appointments
$query = "
    SELECT appointment_date, appointment_time, doctor_name, specialization
    FROM appointments
    WHERE UserID = ? AND status = 'confirmed' AND appointment_date < ?
    ORDER BY appointment_date, appointment_time
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $UserID, $today);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Appointments</title>
    <link rel="stylesheet" type="text/css" href="css/completed_upcoming_appointment.css">
    <style>
    /* Add custom styles for the completed appointments page */
    body.completed {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        padding: 20px;
    }

    h1 {
        font-size: 2em;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    a.button {
        display: inline-block;
        background-color: #c64f00;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
    }

    a.button:hover {
        background-color: #c64f00;
    }
    </style>
</head>

<body class="completed">

    <h1>Completed Appointments</h1>
    <table>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Doctor</th>
            <th>Specialization</th>
        </tr>
        <?php foreach ($appointments as $appointment): ?>
        <tr>
            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
            <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
            <td><?php echo htmlspecialchars($appointment['specialization']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href='dashboard.php' class="button">Back to Dashboard</a>
</body>

</html>