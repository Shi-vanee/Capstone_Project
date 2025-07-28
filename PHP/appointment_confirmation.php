<?php
include('session_handler.php');
require 'vendor/autoload.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "website");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['UserID'])) {
    die("User is not logged in.");
}

// Get and sanitize all form data
$doctor_name = $conn->real_escape_string($_POST['doctor_name'] ?? '');
$specialization = $conn->real_escape_string($_POST['specialization'] ?? '');
$appointment_date = $conn->real_escape_string($_POST['appointment_date'] ?? '');
$appointment_time = $conn->real_escape_string($_POST['appointment_time'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$phone_number = $conn->real_escape_string($_POST['phone_number'] ?? '');
$country = $conn->real_escape_string($_POST['country'] ?? '');
$address = $conn->real_escape_string($_POST['address'] ?? '');
$fName = $conn->real_escape_string($_POST['fName'] ?? '');
$lName = $conn->real_escape_string($_POST['lName'] ?? '');

// Get doctor details from database
$doctor_query = "SELECT id, email, appointment_type, platform, address 
                FROM register_doctor 
                WHERE CONCAT(fName, ' ', lName) = '$doctor_name'";
$result = $conn->query($doctor_query);

if ($result->num_rows > 0) {
    $doctor_row = $result->fetch_assoc();
    $DoctorID = $doctor_row['id'];
    $doctor_email = $doctor_row['email'];
    $appointment_type = $doctor_row['appointment_type'];
    
    // Set platform or address based on appointment type
    if ($appointment_type == 'online') {
        $online_platform = $doctor_row['platform'];
        $clinic_address = 'Online Appointment';
    } else {
        $clinic_address = $doctor_row['address'];
        $online_platform = 'Not Applicable';
    }
} else {
    die("Doctor not found in our system!");
}

// Insert appointment into database
$sql = "INSERT INTO appointments (
        DoctorID, doctor_name, specialization, 
        appointment_date, appointment_time, appointment_type, 
        email, phone_number, country, address, 
        status, UserID, fName, lName, 
        platform, clinic_address
        ) VALUES (
        '$DoctorID', '$doctor_name', '$specialization', 
        '$appointment_date', '$appointment_time', '$appointment_type', 
        '$email', '$phone_number', '$country', '$address', 
        'pending', '{$_SESSION['UserID']}', '$fName', '$lName', 
        '$platform', '$clinic_address'
        )";

if ($conn->query($sql) === TRUE) {
    $appointment_id = $conn->insert_id;

    // Configure PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'trustingmedicare@gmail.com';
        $mail->Password = 'hiwe sakz jnwk jhqd';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('trustingmedicare@gmail.com', 'Trust MediCare');
        $mail->CharSet = 'UTF-8';
        
        // 1. Send to Patient
        $mail->addAddress($email);
        $mail->Subject = "Your Appointment Confirmation #$appointment_id";
        
        // HTML Email Body
        $mail->isHTML(true);
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Appointment Confirmation</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                h2 { color: #e46713; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f8f8f8; }
                .footer { margin-top: 30px; font-size: 0.9em; color: #777; text-align: center; }
                .status-pending { color: #e46713; font-weight: bold; }
            </style>
        </head>
        <body>
            <h2>Appointment Confirmation</h2>
            <p>Dear ' . $fName . ' ' . $lName . ',</p>
            <p>Thank you for booking with Trust MediCare. Here are your appointment details:</p>
            
            <table>
                <tr>
                    <th>Reference Number</th>
                    <td>#' . $appointment_id . '</td>
                </tr>
                <tr>
                    <th>Doctor</th>
                    <td>Dr. ' . $doctor_name . ' (' . $specialization . ')</td>
                </tr>
                <tr>
                    <th>Date & Time</th>
                    <td>' . date('F j, Y', strtotime($appointment_date)) . ' at ' . date('g:i A', strtotime($appointment_time)) . '</td>
                </tr>
                <tr>
                    <th>Appointment Type</th>
                    <td>' . ucfirst(str_replace('-', ' ', $appointment_type)) . '</td>
                </tr>';
        
        if ($appointment_type == 'online') {
            $mail->Body .= '
                <tr>
                    <th>Platform</th>
                    <td>' . $platform . '</td>
                </tr>
                <tr>
                    <th>Meeting Link</th>
                    <td>Will be emailed to you before your appointment</td>
                </tr>';
        } else {
            $mail->Body .= '
                <tr>
                    <th>Clinic Address</th>
                    <td>' . nl2br($clinic_address) . '</td>
                </tr>';
        }
        
        $mail->Body .= '
                <tr>
                    <th>Status</th>
                    <td class="status-pending">Pending confirmation</td>
                </tr>
            </table>
            
            <p>We will notify you once the doctor confirms your appointment. Please allow 24 hours for confirmation.</p>
            
            <div class="footer">
                <p>Need to make changes? Contact us at trustingmedicare@gmail.com</p>
                <p>Best regards,<br>The Trust MediCare Team</p>
            </div>
        </body>
        </html>';
        
        // Plain text version for email clients that don't support HTML
        $mail->AltBody = "Appointment Confirmation\n\n" .
            "Dear $fName $lName,\n\n" .
            "Thank you for booking with Trust MediCare.\n\n" .
            "Reference: #$appointment_id\n" .
            "Doctor: Dr. $doctor_name ($specialization)\n" .
            "Date: " . date('F j, Y', strtotime($appointment_date)) . "\n" .
            "Time: " . date('g:i A', strtotime($appointment_time)) . "\n" .
            "Type: " . ucfirst(str_replace('-', ' ', $appointment_type)) . "\n" .
            ($appointment_type == 'online' ? "Platform: $platform\n" : "Location: $clinic_address\n") .
            "Status: Pending confirmation\n\n" .
            "We will notify you once confirmed.\n\n" .
            "Contact: trustingmedicare@gmail.com\n" .
            "Best regards,\nTrust MediCare Team";
        
        $mail->send();
        
        // 2. Send to Doctor
        $mail->clearAddresses();
        $mail->addAddress($doctor_email);
        $mail->Subject = "New Appointment Request #$appointment_id";
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>New Appointment Request</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                h2 { color: #e46713; }
                table { border-collapse: collapse; width: 100%; margin: 20px 0; }
                th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f8f8f8; }
            </style>
        </head>
        <body>
            <h2>New Appointment Request</h2>
            <p>Dear Dr. ' . $doctor_name . ',</p>
            <p>You have a new appointment request:</p>
            
            <table>
                <tr>
                    <th>Reference</th>
                    <td>#' . $appointment_id . '</td>
                </tr>
                <tr>
                    <th>Patient</th>
                    <td>' . $fName . ' ' . $lName . '</td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td>' . $phone_number . '<br>' . $email . '</td>
                </tr>
                <tr>
                    <th>Date & Time</th>
                    <td>' . date('F j, Y', strtotime($appointment_date)) . ' at ' . date('g:i A', strtotime($appointment_time)) . '</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>' . ucfirst(str_replace('-', ' ', $appointment_type)) . '</td>
                </tr>';
        
        if ($appointment_type == 'online') {
            $mail->Body .= '
                <tr>
                    <th>Your Platform</th>
                    <td>' . $platform . '</td>
                </tr>';
        } else {
            $mail->Body .= '
                <tr>
                    <th>Your Clinic</th>
                    <td>' . nl2br($clinic_address) . '</td>
                </tr>';
        }
        
        $mail->Body .= '
                <tr>
                    <th>Action Required</th>
                    <td style="color: #e46713; font-weight: bold;">Please confirm or reject this appointment</td>
                </tr>
            </table>
            
            <p>Please log in to your doctor dashboard to manage this request.</p>
            <p>Best regards,<br>Trust MediCare</p>
        </body>
        </html>';
        
        $mail->AltBody = "New Appointment Request\n\n" .
            "Dr. $doctor_name,\n\n" .
            "You have a new appointment request:\n\n" .
            "Reference: #$appointment_id\n" .
            "Patient: $fName $lName\n" .
            "Contact: $phone_number / $email\n" .
            "Date: " . date('F j, Y', strtotime($appointment_date)) . "\n" .
            "Time: " . date('g:i A', strtotime($appointment_time)) . "\n" .
            "Type: " . ucfirst(str_replace('-', ' ', $appointment_type)) . "\n" .
            ($appointment_type == 'online' ? "Platform: $platform\n" : "Location: $clinic_address\n") .
            "\nACTION REQUIRED: Pending confirmation\n\n" .
            "Best regards,\nTrust MediCare";
        
        $mail->send();

        header("Location: appointment_details.php?id=$appointment_id");
        exit();

    } catch (Exception $e) {
        error_log("Email Error: " . $e->getMessage());
        // Continue to redirect even if email fails
        header("Location: appointment_details.php?id=$appointment_id");
        exit();
    }
} else {
    die("Error saving appointment: " . $conn->error);
}

$conn->close();
?>