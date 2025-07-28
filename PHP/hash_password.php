<?php
include('connect.php');

// Admin credentials
$email = "admin@gmail.com"; // Replace with your admin email
$plainPassword = "admin123"; // Replace with the plaintext password in the database

// Hash the password
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Update the password in the admin table
$query = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
$query->bind_param("ss", $hashedPassword, $email);
$query->execute();

if ($query->affected_rows > 0) {
    echo "Password hashed and updated successfully!";
} else {
    echo "Failed to update the password. Check your database connection and input values.";
}
?>