<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit();
}

// If form is submitted, update session data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['userName'] = $_POST['name'];
    $_SESSION['userEmail'] = $_POST['email'];
    $_SESSION['userRole'] = $_POST['role']; // Update the role if needed
    
    header('Location: dashboard.php'); // Redirect to dashboard after update
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Update Your Profile</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($_SESSION['userName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($_SESSION['userEmail']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role"
                    value="<?php echo htmlspecialchars($_SESSION['userRole']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
        </form>
    </div>

    <script src="js/bootstrap.js"></script>
</body>

</html>