<?php
session_start();
include('connect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch user details to confirm deletion
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

// Delete user from database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deleteQuery = "DELETE FROM users WHERE UserID = $userID";

    if ($conn->query($deleteQuery)) {
        echo "<p>User deleted successfully. <a href='admin_dashboard.php'>Go back to dashboard</a></p>";
    } else {
        echo "<p>Error deleting user: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="css/deletes_user_styles.css"> <!-- Add your CSS file here -->
</head>

<body>
    <h1>Delete User</h1>

    <p>Are you sure you want to delete the following user?</p>

    <table>
        <tr>
            <th>First Name</th>
            <td><?php echo htmlspecialchars($user['fName']); ?></td>
        </tr>
        <tr>
            <th>Last Name</th>
            <td><?php echo htmlspecialchars($user['lName']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
        </tr>
    </table>

    <!-- Confirmation Form -->
    <form method="POST" action="">
        <button type="submit" class="delete-button">Delete User</button>
        <a href="admin_dashboard.php" class="back-link">Cancel</a>
    </form>
</body>

</html>