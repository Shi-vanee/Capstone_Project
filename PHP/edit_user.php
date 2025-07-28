<?php
// edit_user.php

session_start();
include('connect.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $userId);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Failed to update user information.";
        }
    }

    $result = $conn->query("SELECT * FROM users WHERE id = $userId");
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
</head>

<body>
    <h1>Edit User</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <br>
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</body>

</html>