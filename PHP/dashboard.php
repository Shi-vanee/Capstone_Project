<?php
// Start the session
session_start();

// Check if the user is logged in and has a role set
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connect.php';

// Assign session variables
$role = $_SESSION['role'];

$records = null;

if ($role === 'user') {
    if (!isset($_SESSION['UserID'])) {
        die("Error: UserID not set in session.");
    }
    $UserID = $_SESSION['UserID'];
    
    // Fetch user details
    $query = "SELECT fName, lName, email, phone_number, address, country FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
        if (empty($_POST['patient_id']) || empty($_POST['medical_details'])) {
            echo "Error: All fields are required.";
        } else {
            $patient_id = intval($_POST['patient_id']);
            $medical_details = htmlspecialchars($_POST['medical_details'], ENT_QUOTES, 'UTF-8');
            $doctor_id = null; // Default for users (non-doctor roles)

            $query = "INSERT INTO medical_history (user_id, doctor_id, record, entry_date) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $patient_id, $doctor_id, $medical_details);

            if ($stmt->execute()) {
                echo "Medical history added successfully!";
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }


    
    // Handle appointment removal
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_appointment'])) {
        $appointment_id = $_POST['appointment_id'];
        
        // Delete the appointment from the database
        $delete_query = "DELETE FROM appointments WHERE appointment_id = ? AND UserID = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $appointment_id, $UserID);
        $delete_stmt->execute();
        
        // Redirect to the dashboard to refresh the appointment list
        header("Location: dashboard.php");
        exit();
    }
} elseif ($role === 'doctor') {
    if (!isset($_SESSION['DoctorID'])) {
        die("Error: doctorID not set in session.");
    }
    $doctorID = $_SESSION['DoctorID'];
    
    // Fetch doctor details
    $query = "SELECT fName, lName, email, phone_number, address, country FROM doctors WHERE doctorID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctorID);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
    /* Combined CSS styling */
    .navbar {
        background-color: #333;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .navbar ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .navbar ul li {
        padding: 14px 20px;
    }

    .navbar ul li a {
        text-decoration: none;
        color: white;
        display: block;
    }

    .navbar ul li a:hover {
        background-color: #575757;
        border-radius: 5px;
    }

    .brand-logo {
        color: white;
        font-size: 1.5em;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="brand-logo">Trust MediCare</div>
        <ul>
            <?php if ($role === 'user'): ?>

            <li><a href="#profile">Profile</a></li>
            <li><a href="completed_appointments.php">Completed Appointments</a></li>
            <li><a href="#medical-history">Medical History</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php elseif ($role === 'doctor'): ?>
            <li><a href="register_doctor.php">Register Doctor</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <?php if ($role === 'user'): ?>
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($user['fName'] . ' ' . $user['lName']); ?></h1>
            <p>Your healthcare dashboard is up-to-date</p>
        </header>

        <!-- Profile Section -->
        <section id="profile" class="section">
            <h3>Your Profile</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Name:</h4>
                    <p><?php echo htmlspecialchars($user['fName'] . ' ' . $user['lName']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Email:</h4>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Phone Number:</h4>
                    <p><?php echo htmlspecialchars($user['phone_number'] ?? 'Not Provided'); ?></p>
                </div>
                <div class="info-card">
                    <h4>Address:</h4>
                    <p><?php echo htmlspecialchars($user['address'] ?? 'Not Provided'); ?></p>
                </div>
                <div class="info-card">
                    <h4>Country:</h4>
                    <p><?php echo htmlspecialchars($user['country'] ?? 'Not Provided'); ?></p>
                </div>
            </div>
            <a href='edit_user_details.php' class="button">Edit Your Details</a>
        </section>

        <!-- Medical History Section -->
        <section id="medical-history" class="section">
            <h3>Medical History</h3>
            <ul>
                <?php
                $query = "SELECT record, entry_date FROM medical_history WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $UserID);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row['record']) . " (" . htmlspecialchars($row['entry_date']) . ")</li>";
                    }
                } else {
                    echo "<li>No records found.</li>";
                }
                ?>
            </ul>
        </section>


        <!-- Additional Links -->
        <section id="additional-links" class="section">
            <h3>Actions</h3>
            <a href='contact.php' class="button" style="margin-bottom: 10px;">View Doctors and Book Appointments</a><br>
            <a href='index.php' class="button" style="margin-bottom: 10px;">Return to Homepage</a>
        </section>

        <?php elseif ($role === 'doctor'): ?>
        <header>
            <h1>Welcome, Dr. <?php echo htmlspecialchars($doctor['fName'] . ' ' . $doctor['lName']); ?></h1>
            <p>Manage your daily tasks, appointments, and patient information</p>
        </header>

        <!-- Profile Section -->
        <section id="profile" class="section">
            <h3>Your Profile</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Name:</h4>
                    <p>Dr. <?php echo htmlspecialchars($doctor['fName'] . ' ' . $doctor['lName']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Email:</h4>
                    <p><?php echo htmlspecialchars($doctor['email']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Phone Number:</h4>
                    <p><?php echo htmlspecialchars($doctor['phone_number']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Address:</h4>
                    <p><?php echo htmlspecialchars($doctor['address']); ?></p>
                </div>
                <div class="info-card">
                    <h4>Country:</h4>
                    <p><?php echo htmlspecialchars($doctor['country']); ?></p>
                </div>
            </div>
            <a href='edit_doctor_detail.php' class="button">Edit Your Details</a>
        </section>

        <!-- Additional Links -->
        <section id="additional-links" class="section">
            <h3>Actions</h3>
            <a href='view_appointment.php' class="button" style="margin-bottom: 10px;">View All Appointments</a><br>
            <a href='index.php' class="button" style="margin-bottom: 10px;">Return to Homepage</a>
        </section>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Trust MediCare. All rights reserved.</p>
    </footer>
</body>

</html>