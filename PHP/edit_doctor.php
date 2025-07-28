<?php
// edit_doctor.php

session_start();
include('connect.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $doctorId = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $specialization = $_POST['specialization'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $specialization, $email, $phone, $doctorId);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Failed to update doctor information.";
        }
    }

    $result = $conn->query("SELECT * FROM doctors WHERE id = $doctorId");
    $doctor = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Doctor</title>
</head>

<body>
    <h1>Edit Doctor</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $doctor['name']; ?>" required>
        <br>
        <label>Specialization:</label>
        <input type="text" name="specialization" value="<?php echo $doctor['specialization']; ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $doctor['email']; ?>" required>
        <br>
        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $doctor['phone']; ?>" required>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</body>

</html>