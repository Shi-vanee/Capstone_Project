<?php
include('session_handler.php'); 
include('connect.php');

$query = "SELECT * FROM register_doctor";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Doctors</title>
    <link rel="stylesheet" href="css/contact_style.css">
    <style>
    .doctor-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .doctor-name {
        color: #e46713;
        margin-bottom: 15px;
    }

    .availability {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
    }

    .btn-success {
        background-color: #e46713;
        color: white;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
        margin-top: 10px;
    }

    .btn-success:hover {
        background-color: #e46713;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .col-md-6 {
        flex: 0 0 calc(50% - 20px);
    }

    .col-lg-4 {
        flex: 0 0 calc(33.333% - 20px);
    }
    </style>
</head>

<body>

    <header>
        <h1>Our Doctors</h1>
        <p>Browse our list of doctors to find the right one for you. Book appointments online or in person.</p>
    </header>

    <section class="doctor-profiles">
        <div class="container">
            <div class="row">
                <?php 
                if ($result->num_rows > 0) {
                    while ($doctor = $result->fetch_assoc()) {
                        $doctorName = $doctor['fName'] . ' ' . $doctor['lName'];
                        $specialization = $doctor['specialization'];
                        $email = $doctor['email'];
                        $phone = $doctor['phone_number'];
                        $appointmentType = strtolower($doctor['appointment_type']); // Convert to lowercase
                        $address = $doctor['address'];
                        $platform = $doctor['platform'];
                        $available_time = $doctor['available_time'] ?? 'Not specified';
                ?>
                <div class="col-md-6 col-lg-4 doctor-card">
                    <h2 class="doctor-name"><?php echo htmlspecialchars($doctorName); ?></h2>
                    <p><strong>Specialization:</strong> <?php echo htmlspecialchars($specialization); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>

                    <div class="availability">
                        <strong>Available Time:</strong> <?php echo htmlspecialchars($available_time); ?>
                    </div>

                    <p><strong>Appointment Type:</strong>
                        <?php if ($appointmentType == "face-to-face") { ?>
                        <span>Face-to-Face</span>
                    <p><strong>Clinic: </strong><?php echo htmlspecialchars($address); ?></p>
                    <?php } else { ?>
                    <span>Online</span>
                    <p><strong>Platform: </strong><?php echo htmlspecialchars($platform); ?></p>
                    <?php } ?>
                    </p>

                    <a href="appointment.html?doctor_name=<?php echo urlencode($doctorName); ?>&specialization=<?php echo urlencode($specialization); ?>&available_time=<?php echo urlencode($available_time); ?>&appointment_type=<?php echo urlencode($appointmentType); ?>&platform=<?php echo urlencode($platform); ?>&clinic_address=<?php echo urlencode($address); ?>"
                        class="btn btn-success appointment-button">Book Appointment</a>
                </div>
                <?php 
                    }
                } else {
                    echo "<p>No doctors available at the moment.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Trust MediCare</p>
    </footer>

</body>

</html>