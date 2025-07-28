<?php
// Include database connection
include 'connect.php';

// Country codes array
$country_codes = ['+230', '+1', '+44', '+91', '+33', '+49', '+81'];

// Platform options
$platform_options = ['Google Meet', 'Microsoft Teams'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fName = trim($_POST['fName']);
    $lName = trim($_POST['lName']);
    $specialization = $_POST['specialization'];
    $email = strtolower(trim($_POST['email']));
    $country_code = $_POST['country_code'];
    $phone = trim($_POST['phone_number']);
    $full_phone = $country_code . $phone;
    $appointment_type = $_POST['appointment_type'];
    
    // Handle conditional fields
    $address = ($_POST['appointment_type'] === 'Face-to-Face') ? trim($_POST['address']) : 'Not Applicable';
    $platform = ($_POST['appointment_type'] === 'Online') ? trim($_POST['platform']) : 'Not Applicable';
    
    // Format available time
    $available_time = $_POST['start_time'] . ' ' . $_POST['start_period'] . ' to ' . 
                     $_POST['end_time'] . ' ' . $_POST['end_period'];
    
    // Set registration date
    $registration_date = date('Y-m-d H:i:s');

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check for duplicate email
        $check_query = "SELECT email FROM register_doctor WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error_message = "This email is already registered.";
        } else {
            // Insert into register_doctor table
            $insert_query = "INSERT INTO register_doctor 
                            (fName, lName, specialization, email, phone_number, 
                             appointment_type, address, platform, available_time, registration_date)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ssssssssss", 
                $fName, $lName, $specialization, $email, $full_phone, 
                $appointment_type, $address, $platform, $available_time, $registration_date);

            if ($insert_stmt->execute()) {
                $success_message = "Doctor registration submitted successfully!";
                $_POST = array(); // Clear form on success
            } else {
                $error_message = "Registration failed. Error: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #c64f00;
        padding-bottom: 10px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .phone-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .phone-group select {
        width: 30%;
    }

    .phone-group input {
        flex: 1;
    }

    .time-container {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        align-items: center;
    }

    .time-group {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .time-group select {
        padding: 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .time-separator {
        font-weight: bold;
        padding: 0 10px;
    }

    button {
        background-color: #c64f00;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #c64f00;
    }

    .return-btn {
        background-color: #95a5a6;
        margin-top: 20px;
    }

    .return-btn:hover {
        background-color: #7f8c8d;
    }

    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .hide {
        display: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Doctor Registration Form</h2>

        <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="register_doctor.php" method="POST">
            <!-- Personal Information -->
            <label for="fName">First Name:</label>
            <input type="text" id="fName" name="fName" value="<?= htmlspecialchars($_POST['fName'] ?? '') ?>" required>

            <label for="lName">Last Name:</label>
            <input type="text" id="lName" name="lName" value="<?= htmlspecialchars($_POST['lName'] ?? '') ?>" required>

            <label for="specialization">Specialization:</label>
            <select name="specialization" id="specialization" required>
                <option value="">Select Specialization</option>
                <option value="Psychiatrist"
                    <?= (isset($_POST['specialization'])) && $_POST['specialization'] === 'Psychiatrist' ? 'selected' : '' ?>>
                    Psychiatrist</option>
                <option value="Therapist"
                    <?= (isset($_POST['specialization'])) && $_POST['specialization'] === 'Therapist' ? 'selected' : '' ?>>
                    Therapist</option>
            </select>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label>Phone Number:</label>
            <div class="phone-group">
                <select name="country_code" required>
                    <option value="">Code</option>
                    <?php foreach ($country_codes as $code): ?>
                    <option value="<?= htmlspecialchars($code) ?>"
                        <?= (isset($_POST['country_code']) && $_POST['country_code'] === $code ? 'selected' : '') ?>>
                        <?= htmlspecialchars($code) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="phone_number" placeholder="Phone number"
                    value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>" required>
            </div>

            <!-- Appointment Details -->
            <label for="appointment_type">Appointment Type:</label>
            <select name="appointment_type" id="appointment_type" required onchange="toggleFields()">
                <option value="">Select Type</option>
                <option value="Face-to-Face"
                    <?= (isset($_POST['appointment_type'])) && $_POST['appointment_type'] === 'Face-to-Face' ? 'selected' : '' ?>>
                    Face-to-Face</option>
                <option value="Online"
                    <?= (isset($_POST['appointment_type'])) && $_POST['appointment_type'] === 'Online' ? 'selected' : '' ?>>
                    Online</option>
            </select>

            <div id="addressField">
                <label for="address">Clinic Address (if Face-to-Face):</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
            </div>

            <div id="platformField">
                <label for="platform">Platform (if Online):</label>
                <select name="platform" id="platform">
                    <option value="">Select Platform</option>
                    <?php foreach ($platform_options as $option): ?>
                    <option value="<?= htmlspecialchars($option) ?>"
                        <?= (isset($_POST['platform'])) && $_POST['platform'] === $option ? 'selected' : '' ?>>
                        <?= htmlspecialchars($option) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Availability -->
            <label>Available Hours:</label>
            <div class="time-container">
                <div class="time-group">
                    <select name="start_time" required>
                        <option value="">Start Time</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>:00"
                            <?= (isset($_POST['start_time'])) && $_POST['start_time'] === "$i:00" ? 'selected' : '' ?>>
                            <?= $i ?>:00
                        </option>
                        <?php endfor; ?>
                    </select>
                    <select name="start_period" required>
                        <option value="AM"
                            <?= (isset($_POST['start_period'])) && $_POST['start_period'] === 'AM' ? 'selected' : '' ?>>
                            AM</option>
                        <option value="PM"
                            <?= (isset($_POST['start_period'])) && $_POST['start_period'] === 'PM' ? 'selected' : '' ?>>
                            PM</option>
                    </select>
                </div>

                <span class="time-separator">to</span>

                <div class="time-group">
                    <select name="end_time" required>
                        <option value="">End Time</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>:00"
                            <?= (isset($_POST['end_time'])) && $_POST['end_time'] === "$i:00" ? 'selected' : '' ?>>
                            <?= $i ?>:00
                        </option>
                        <?php endfor; ?>
                    </select>
                    <select name="end_period" required>
                        <option value="AM"
                            <?= (isset($_POST['end_period'])) && $_POST['end_period'] === 'AM' ? 'selected' : '' ?>>AM
                        </option>
                        <option value="PM"
                            <?= (isset($_POST['end_period'])) && $_POST['end_period'] === 'PM' ? 'selected' : '' ?>>PM
                        </option>
                    </select>
                </div>
            </div>

            <button type="submit">Register Doctor</button>
        </form>

        <a href="dashboard.php">
            <button class="return-btn">Return to Dashboard</button>
        </a>
    </div>

    <script>
    function toggleFields() {
        const type = document.getElementById('appointment_type').value;
        const addressField = document.getElementById('addressField');
        const platformField = document.getElementById('platformField');

        if (type === 'Online') {
            addressField.style.display = 'none';
            platformField.style.display = 'block';
            document.getElementById('address').required = false;
            document.getElementById('platform').required = true;
        } else if (type === 'Face-to-Face') {
            addressField.style.display = 'block';
            platformField.style.display = 'none';
            document.getElementById('address').required = true;
            document.getElementById('platform').required = false;
        } else {
            addressField.style.display = 'block';
            platformField.style.display = 'block';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
    });
    </script>
</body>

</html>