<?php
session_start();
require 'connect.php'; // Database connection file

// Check if the doctor is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor' || !isset($_SESSION['DoctorID'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$error = null;
$success = null;
$doctorID = $_SESSION['DoctorID'];

// Fetch doctor's details from database
$doctor_stmt = $conn->prepare("SELECT fName, lName FROM doctors WHERE DoctorID = ?");
$doctor_stmt->bind_param('i', $doctorID);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();

if ($doctor_result->num_rows === 0) {
    die("Doctor not found in database");
}

$doctor = $doctor_result->fetch_assoc();
$doctorName = "Dr. " . htmlspecialchars($doctor['fName']) . ' ' . htmlspecialchars($doctor['lName']);

// Fetch appointments
$appointments = [];
$patients = [];
$stmt = $conn->prepare("
    SELECT a.appointment_id, u.UserID, u.fName, u.lName, 
           a.appointment_date, a.appointment_time 
    FROM appointments a 
    JOIN users u ON a.UserID = u.UserID 
    WHERE a.DoctorID = ? 
    ORDER BY a.appointment_date, a.appointment_time
");
$stmt->bind_param('i', $doctorID);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

// Get distinct patients
$patients_stmt = $conn->prepare("
    SELECT DISTINCT u.UserID, u.fName, u.lName 
    FROM appointments a 
    JOIN users u ON a.UserID = u.UserID 
    WHERE a.DoctorID = ?
    ORDER BY u.lName, u.fName
");
$patients_stmt->bind_param('i', $doctorID);
$patients_stmt->execute();
$patients_result = $patients_stmt->get_result();
if ($patients_result) {
    $patients = $patients_result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    if (empty($_POST['patient_id']) || empty($_POST['medical_details'])) {
        $error = "All fields are required";
    } else {
        $patient_id = (int)$_POST['patient_id'];
        $medical_details = htmlspecialchars(trim($_POST['medical_details']), ENT_QUOTES, 'UTF-8');
        
        // Verify patient belongs to this doctor
        $verify_stmt = $conn->prepare("
            SELECT 1 FROM appointments 
            WHERE UserID = ? AND DoctorID = ? 
            LIMIT 1
        ");
        $verify_stmt->bind_param("ii", $patient_id, $doctorID);
        $verify_stmt->execute();
        
        if ($verify_stmt->get_result()->num_rows > 0) {
            $insert_stmt = $conn->prepare("
                INSERT INTO medical_history 
                (user_id, doctor_id, record, entry_date) 
                VALUES (?, ?, ?, NOW())
            ");
            $insert_stmt->bind_param("iis", $patient_id, $doctorID, $medical_details);
            
            if ($insert_stmt->execute()) {
                $success = "Note added successfully!";
                // Refresh to show changes
                header("Location: view_appointment.php");
                exit();
            } else {
                $error = "Database error: " . $conn->error;
            }
            $insert_stmt->close();
        } else {
            $error = "Invalid patient selection";
        }
        $verify_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments - <?php echo $doctorName; ?></title>
    <style>
    :root {
        --primary: #e46713;
        --primary-dark: #c45a10;
        --secondary: #6c757d;
        --light: #f8f9fa;
        --dark: #343a40;
        --white: #ffffff;
        --border: #eaeaea;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light);
        color: var(--dark);
        line-height: 1.6;
        padding: 20px;
    }

    .dashboard {
        max-width: 1200px;
        margin: 0 auto;
        background: var(--white);
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    header {
        padding: 25px;
        background: var(--primary);
        color: var(--white);
        text-align: center;
    }

    h1 {
        font-size: 1.8rem;
        margin-bottom: 5px;
    }

    .welcome-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .content {
        display: flex;
        padding: 20px;
        gap: 25px;
    }

    .appointments {
        flex: 2;
    }

    .records {
        flex: 1;
    }

    .panel {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }

    .panel-title {
        font-size: 1.2rem;
        color: var(--dark);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge {
        background: var(--primary);
        color: var(--white);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th {
        background: var(--primary);
        color: var(--white);
        padding: 12px;
        text-align: left;
        font-weight: 500;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 4px;
        font-family: inherit;
    }

    textarea {
        min-height: 150px;
        resize: vertical;
    }

    .btn {
        display: inline-block;
        background: var(--primary);
        color: var(--white);
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn:hover {
        background: var(--primary-dark);
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        color: var(--secondary);
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .content {
            flex-direction: column;
        }

        header {
            padding: 15px;
        }

        h1 {
            font-size: 1.5rem;
        }
    }
    </style>
</head>

<body>
    <div class="dashboard">
        <header>
            <h1>Welcome, <?php echo $doctorName; ?></h1>
            <p class="welcome-subtitle">Appointment Management Dashboard</p>
        </header>

        <div class="content">
            <div class="appointments">
                <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="panel">
                    <h2 class="panel-title">
                        Upcoming Appointments
                    </h2>

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appt['appointment_id']); ?></td>
                                <td><?php echo htmlspecialchars($appt['fName'].' '.$appt['lName']); ?></td>
                                <td><?php echo htmlspecialchars($appt['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($appt['appointment_time']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No appointments scheduled</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="records">
                <div class="panel">
                    <h2 class="panel-title">
                        Patient Records
                        <span class="badge"><?php echo count($patients); ?> patients</span>
                    </h2>

                    <form method="POST">
                        <div class="form-group">
                            <label for="patient_id">Patient</label>
                            <select name="patient_id" id="patient_id" required>
                                <?php if (!empty($patients)): ?>
                                <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo htmlspecialchars($patient['UserID']); ?>">
                                    <?php echo htmlspecialchars($patient['fName'].' '.$patient['lName']); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <option value="" disabled selected>No patients available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="medical_details">Medical Notes</label>
                            <textarea name="medical_details" id="medical_details"
                                placeholder="Enter examination findings, diagnosis, and treatment..."
                                required></textarea>
                        </div>

                        <button type="submit" name="add_note" class="btn btn-block">
                            Save Patient Record
                        </button>
                    </form>
                </div>

                <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>
<?php
// Clean up
if (isset($stmt)) $stmt->close();
if (isset($patients_stmt)) $patients_stmt->close();
if (isset($doctor_stmt)) $doctor_stmt->close();
$conn->close();
?>