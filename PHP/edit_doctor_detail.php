<?php
// Start the session
session_start();

// Check if the user is logged in and has a role set
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connect.php';

// Assign session variable
$DoctorID = $_SESSION['DoctorID'];

// Fetch current doctor details
$query = "SELECT fName, lName, email, phone_number, country, address, specialization FROM doctors WHERE DoctorID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $DoctorID);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

// Country codes and countries arrays
$country_codes = ["+230", "+1", "+44", "+91"]; // Add more as needed
$countries = ["Mauritius", "United States", "United Kingdom", "India"]; // Add more as needed
$specializations = ["Psychiatrist", "Therapist"]; // Dropdown options for specialization

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $specialization = $_POST['specialization'];

    // Update doctor details in the database
    $update_query = "
        UPDATE doctors 
        SET fName = ?, lName = ?, email = ?, phone_number = ?, country = ?, address = ?, specialization = ?
        WHERE DoctorID = ?
    ";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssssi", $fName, $lName, $email, $phone_number, $country, $address, $specialization, $DoctorID);
    $update_stmt->execute();

    // Redirect to doctor dashboard after successful update
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor Details</title>
    <link rel="stylesheet" href="css/edit_doctor_detail.css">
</head>

<body>

    <h1>Edit Your Details</h1>

    <form method="POST">
        <label for="fName">First Name:</label>
        <input type="text" id="fName" name="fName" value="<?= htmlspecialchars($doctor['fName']) ?>" required>

        <label for="lName">Last Name:</label>
        <input type="text" id="lName" name="lName" value="<?= htmlspecialchars($doctor['lName']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($doctor['email']) ?>" required>

        <label for="phone_number">Phone Number:</label>
        <div>
            <select name="country_code" required>
                <?php foreach ($country_codes as $code): ?>
                <option value="<?= $code ?>"
                    <?= $code == substr($doctor['phone_number'], 0, strlen($code)) ? 'selected' : '' ?>>
                    <?= $code ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="phone_number" name="phone_number"
                value="<?= htmlspecialchars(substr($doctor['phone_number'], strlen(substr($doctor['phone_number'], 0, strlen($doctor['phone_number']))))) ?>"
                required>
        </div>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?= htmlspecialchars($doctor['address']) ?></textarea>

        <label for="specialization">Specialization:</label>
        <select id="specialization" name="specialization" required>
            <?php foreach ($specializations as $specialization_option): ?>
            <option value="<?= $specialization_option ?>"
                <?= $specialization_option == $doctor['specialization'] ? 'selected' : '' ?>>
                <?= $specialization_option ?>
            </option>
            <?php endforeach; ?>
        </select>

        <label for="country">Country:</label>
        <select id="country" name="country" required>
            <?php foreach ($countries as $country): ?>
            <option value="<?= $country ?>" <?= $country == $doctor['country'] ? 'selected' : '' ?>>
                <?= $country ?>
            </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Changes</button>
    </form>

    <a href="dashboard.php">Cancel</a>

</body>

</html>