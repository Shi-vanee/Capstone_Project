<?php
// Start the session
session_start();

// Include database connection
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Fetch the UserID from the session
$user_id = $_SESSION['UserID'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $phone_code = $_POST['phone_code'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $country = $_POST['country'];

    // Combine phone code and number
    $full_phone = $phone_code . $phone;

    // Update user details in the database
    $query = $conn->prepare("
        UPDATE users 
        SET fName = ?, lName = ?, phone_number = ?, address = ?, country = ? 
        WHERE UserID = ?
    ");
    $query->bind_param("sssssi", $fName, $lName, $full_phone, $address, $country, $user_id);
    $query->execute();

    // Redirect back to the dashboard
    header('Location: dashboard.php');
    exit();
}

// Fetch current user details
$query = $conn->prepare("SELECT fName, lName, phone_number, address, country FROM users WHERE UserID = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Extract phone code and phone number (if applicable)
$phone_code = substr($user['phone_number'] ?? '', 0, strpos($user['phone_number'] ?? '', '-') ?: 0);
$phone_number = substr($user['phone_number'] ?? '', strpos($user['phone_number'] ?? '', '-') + 1);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Your Details</title>
    <link rel="stylesheet" type="text/css" href="css/edit_user_details.css"> <!-- Add your CSS file here -->
</head>

<body>
    <div class="container">

        <h1>Edit Your Profile</h1>
        <form method="POST">
            <label for="fName">First Name:</label>
            <input type="text" id="fName" name="fName" value="<?= htmlspecialchars($user['fName'] ?? '') ?>"
                required><br>

            <label for="lName">Last Name:</label>
            <input type="text" id="lName" name="lName" value="<?= htmlspecialchars($user['lName'] ?? '') ?>"
                required><br>

            <label for="phone">Phone Number:</label>
            <div>
                <select id="phone_code" name="phone_code" required>
                    <option value="+230" <?= (strpos($phone_code, '+230') !== false) ? 'selected' : '' ?>>+230
                        (Mauritius)</option>
                    <option value="+1" <?= (strpos($phone_code, '+1') !== false) ? 'selected' : '' ?>>+1 (USA)</option>
                    <option value="+44" <?= (strpos($phone_code, '+44') !== false) ? 'selected' : '' ?>>+44 (UK)
                    </option>
                    <option value="+61" <?= (strpos($phone_code, '+61') !== false) ? 'selected' : '' ?>>+61 (Australia)
                    </option>
                    <option value="+91" <?= (strpos($phone_code, '+91') !== false) ? 'selected' : '' ?>>+91 (India)
                    </option>
                    <!-- Add more country codes as needed -->
                </select>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone_number ?? '') ?>" required
                    placeholder="Phone Number">
            </div><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address"
                required><?= htmlspecialchars($user['address'] ?? '') ?></textarea><br>

            <label for="country">Country:</label>
            <select id="country" name="country" required>
                <option value="Mauritius" <?= ($user['country'] === 'Mauritius') ? 'selected' : '' ?>>Mauritius</option>
                <option value="USA" <?= ($user['country'] === 'USA') ? 'selected' : '' ?>>USA</option>
                <option value="Australia" <?= ($user['country'] === 'Australia') ? 'selected' : '' ?>>Australia</option>
                <option value="UK" <?= ($user['country'] === 'UK') ? 'selected' : '' ?>>United Kingdom</option>
                <option value="India" <?= ($user['country'] === 'India') ? 'selected' : '' ?>>India</option>
                <option value="Others" <?= ($user['country'] === 'Others') ? 'selected' : '' ?>>Others</option>
                <!-- Add more countries as needed -->
            </select><br>

            <button type="submit">Save Changes</button>
        </form>
        <a href="dashboard.php">Cancel</a>
    </div>
</body>

</html>