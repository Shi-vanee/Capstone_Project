<?php
session_start();
include('connect.php');
require 'vendor/autoload.php';

function generateMeetingLink($platform) {
    // Generate different meeting links based on platform
    switch(strtolower($platform)) {
        case 'google meet':
            return "https://meet.google.com/new-" . bin2hex(random_bytes(4));
        case 'microsoft teams':
            return "https://teams.microsoft.com/l/meeting/new?meetingId=" . bin2hex(random_bytes(8));
        case 'zoom':
            return "https://zoom.us/j/" . rand(100000000, 999999999);
        default:
            return "Meeting link will be provided before the appointment";
    }
}

function getPatientEmailBody($status, $name, $id, $date, $time, $doctor, $type, $platform, $address, $meeting_link = '') {
    $date_formatted = date('F j, Y', strtotime($date));
    $time_formatted = date('g:i a', strtotime($time));
    
    if ($status == 'confirmed') {
        $details = ($type == 'online') 
            ? "<tr><th>Online Platform:</th><td><strong>$platform</strong></td></tr>
               <tr><th>Meeting Link:</th><td><a href='$meeting_link'>$meeting_link</a></td></tr>"
            : "<tr><th>Clinic Address:</th><td><strong>$address</strong></td></tr>";

        return "
        <html>
        <head><title>Appointment Confirmed</title></head>
        <body style='font-family: Arial, sans-serif;'>
            <h2 style='color: #e46713;'>Appointment Confirmed</h2>
            <p>Dear $name,</p>
            <p>Your appointment has been confirmed. Details:</p>
            <table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>
                <tr><th style='width: 30%;'>Reference:</th><td>#$id</td></tr>
                <tr><th>Doctor:</th><td>$doctor</td></tr>
                <tr><th>Date/Time:</th><td>$date_formatted at $time_formatted</td></tr>
                <tr><th>Type:</th><td>" . ucfirst($type) . "</td></tr>
                $details
            </table>
            <p style='margin-top: 20px;'>Thank you for choosing Trust MediCare.</p>
        </body>
        </html>";
    } else {
        return "...rejection email template...";
    }
}

// Main script execution
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = $_GET['id'];
    $status = $_GET['status'];

    if (!in_array($status, ['confirmed', 'rejected'])) {
        die("Invalid status.");
    }

    // First update the status
    $update_stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
    if ($update_stmt === false) {
        die("Error preparing update statement: " . $conn->error);
    }
    $update_stmt->bind_param("si", $status, $appointment_id);
    
    if ($update_stmt->execute()) {
        // Get complete appointment details with doctor's information
        $query = "SELECT 
                    a.*, 
                    r.email as doctor_email,
                    r.platform as doctor_platform,
                    r.address as doctor_address
                  FROM appointments a
                  JOIN register_doctor r ON a.doctor_name = CONCAT(r.fName, ' ', r.lName)
                  WHERE a.appointment_id = ?";
        
        $select_stmt = $conn->prepare($query);
        if ($select_stmt === false) {
            die("Error preparing select statement: " . $conn->error);
        }
        $select_stmt->bind_param("i", $appointment_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $appointment = $result->fetch_assoc();
            $user_email = $appointment['email'];
            $doctor_email = $appointment['doctor_email'];
            $user_name = $appointment['fName'] . ' ' . $appointment['lName'];
            $appointment_date = $appointment['appointment_date'];
            $appointment_time = $appointment['appointment_time'];
            $doctor_name = $appointment['doctor_name'];
            $appointment_type = $appointment['appointment_type'];
            
            // Get platform/address - first try appointment record, fallback to doctor's default
            $platform = !empty($appointment['platform']) 
                ? $appointment['platform'] 
                : $appointment['doctor_platform'];
                
            $clinic_address = !empty($appointment['clinic_address']) 
                ? $appointment['clinic_address'] 
                : $appointment['doctor_address'];

            // Generate meeting link for online appointments
            $meeting_link = '';
            if ($status == 'confirmed' && $appointment_type == 'online') {
                $meeting_link = generateMeetingLink($platform);
                // Store the meeting link in the database
                $conn->query("UPDATE appointments SET meeting_link = '$meeting_link' WHERE appointment_id = $appointment_id");
            }

            // Create PHPMailer instance
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'trustingmedicare@gmail.com';
                $mail->Password = 'gdaz xvoh kvwb yxce';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('trustingmedicare@gmail.com', 'Trust MediCare');
                $mail->CharSet = 'UTF-8';

                // 1. Send to Patient
                $mail->addAddress($user_email);
                $mail->Subject = $status == 'confirmed' 
                    ? "Appointment Confirmed #$appointment_id" 
                    : "Appointment Rejected #$appointment_id";
                
                $mail->isHTML(true);
                $mail->Body = getPatientEmailBody(
                    $status, $user_name, $appointment_id, 
                    $appointment_date, $appointment_time, $doctor_name, 
                    $appointment_type, $platform, $clinic_address, $meeting_link
                );
                
                if ($mail->send()) {
                    // 2. Send to Doctor (if confirmed)
                    if ($status == 'confirmed') {
                        $mail->clearAddresses();
                        $mail->addAddress($doctor_email);
                        $mail->Subject = "Appointment Confirmed #$appointment_id";
                        
                        $doctor_details = ($appointment_type == 'online')
                            ? "<tr><th>Platform:</th><td>$platform</td></tr>
                               <tr><th>Meeting Link:</th><td><a href='$meeting_link'>$meeting_link</a></td></tr>"
                            : "<tr><th>Clinic:</th><td>$clinic_address</td></tr>";
                        
                        $mail->Body = "
                        <html>
                        <head><title>Appointment Confirmed</title></head>
                        <body style='font-family: Arial, sans-serif;'>
                            <h2 style='color: #e46713;'>Appointment Confirmed</h2>
                            <p>Dear Dr. $doctor_name,</p>
                            <p>You have confirmed an appointment:</p>
                            <table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>
                                <tr><th style='width: 30%;'>Reference:</th><td>#$appointment_id</td></tr>
                                <tr><th>Patient:</th><td>$user_name</td></tr>
                                <tr><th>Date/Time:</th><td>$appointment_date at $appointment_time</td></tr>
                                <tr><th>Type:</th><td>" . ucfirst($appointment_type) . "</td></tr>
                                $doctor_details
                            </table>
                        </body>
                        </html>";
                        
                        $mail->send();
                    }

                    $_SESSION['message'] = "Appointment $status and notifications sent.";
                    header("Location: admin_dashboard_test.php");
                    exit();
                } else {
                    throw new Exception("Failed to send patient email");
                }

            } catch (Exception $e) {
                $_SESSION['error'] = "Email Error: " . $e->getMessage();
                header("Location: admin_dashboard_test.php");
                exit();
            }
        } else {
            die("Appointment not found.");
        }
    } else {
        die("Error updating appointment: " . $update_stmt->error);
    }
} else {
    die("Invalid request.");
}
?>