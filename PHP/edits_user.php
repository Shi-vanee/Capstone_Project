<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch user details to edit
if (isset($_GET['id'])) {
    $userID = intval($_GET['id']);
    $userQuery = $conn->query("SELECT * FROM users WHERE UserID = $userID");

    if ($userQuery->num_rows > 0) {
        $user = $userQuery->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid user ID.";
    exit();
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $conn->real_escape_string($_POST['fName']);
    $lastName = $conn->real_escape_string($_POST['lName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone_number']);

    $updateQuery = "UPDATE users SET fName = '$firstName', lName = '$lastName', email = '$email', phone_number = '$phone' WHERE UserID = $userID";

    if ($conn->query($updateQuery)) {
        echo "<p>User updated successfully. <a href='admin_dashboard.php'>Go back to dashboard</a></p>";
    } else {
        echo "<p>Error updating user: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/edits_user_styles.css"> <!-- Add your CSS file here -->
</head>

<body>
    <h1>Edit User Details</h1>

    <form method="POST" action="">
        <label for="fName">First Name:</label><br>
        <input type="text" id="fName" name="fName" value="<?php echo htmlspecialchars($user['fName']); ?>"
            required><br><br>

        <label for="lName">Last Name:</label><br>
        <input type="text" id="lName" name="lName" value="<?php echo htmlspecialchars($user['lName']); ?>"
            required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
            required><br><br>

        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number"
            value="<?php echo htmlspecialchars($user['phone_number']); ?>" required><br><br>

        <button type="submit">Update User</button>
    </form>

    <a href="manage_users.php" class="back-link">Back to Dashboard</a>
</body>

</html>