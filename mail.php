<?php
// Include PHPMailer classes from the 'src' folder with your specific path
require 'C:\xampp\htdocs\myform\PHPMailer-master\src\Exception.php';
require 'C:\xampp\htdocs\myform\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\htdocs\myform\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Safely collect form data using fallback 'Not Provided' if a field is empty
    $first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : 'Not Provided';
    $last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : 'Not Provided';
    $email = !empty($_POST['email']) ? $_POST['email'] : 'Not Provided';
    $mobile = !empty($_POST['mobile']) ? $_POST['mobile'] : 'Not Provided';
    $address = !empty($_POST['address']) ? $_POST['address'] : 'Not Provided';
    $preferred_date = !empty($_POST['date']) ? $_POST['date'] : 'Not Provided';
    $preferred_time = !empty($_POST['time']) ? $_POST['time'] : 'Not Provided';
    $location = !empty($_POST['location']) ? $_POST['location'] : 'Not Provided'; // New location field

    // Handle checkbox options for 'Test Details'
    $test_details_checkboxes = isset($_POST['optionsRadios']) ? implode(', ', $_POST['optionsRadios']) : 'Not Selected';
    $test_details_text = !empty($_POST['test']) ? $_POST['test'] : 'Not Provided';

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings using your Mailtrap credentials
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';  // Mailtrap's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = '2cff9c577ca462';  // Your Mailtrap username
        $mail->Password = 'cd20a6ffb91545';  // Your Mailtrap password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Encryption
        $mail->Port = 587;  // Port for TLS

        // Set the sender and recipient
        $mail->setFrom('mailformylaptop@gmail.com', 'ML Medical Centre');  // Your sender email
        $mail->addAddress('recipient@example.com', 'ML Admin');  // Replace with the recipient email

        // Handle multiple file uploads
        if (isset($_FILES['upload']) && count($_FILES['upload']['name']) > 0) {
            $fileCount = count($_FILES['upload']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['upload']['error'][$i] == UPLOAD_ERR_OK) {
                    $uploadfile = $_FILES['upload']['tmp_name'][$i];
                    $filename = $_FILES['upload']['name'][$i];

                    // Add each file as an attachment
                    $mail->addAttachment($uploadfile, $filename);
                    echo "File '$filename' attached successfully.<br>";
                } else {
                    echo "Error uploading file: " . $_FILES['upload']['name'][$i] . " - Error Code: " . $_FILES['upload']['error'][$i] . "<br>";
                }
            }
        } else {
            echo "No files were uploaded.<br>";
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Test Booking from ML Medical Centre';
        $mail->Body    = "
            <h1>Test Booking Details</h1>
            <p><strong>First Name:</strong> $first_name</p>
            <p><strong>Last Name:</strong> $last_name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Mobile:</strong> $mobile</p>
            <p><strong>Address:</strong> $address</p>
            <p><strong>Location:</strong> $location</p> <!-- New location line -->
            <p><strong>Additional Tests:</strong> $test_details_checkboxes</p>
            <p><strong>Test Description:</strong> $test_details_text</p>
            <p><strong>Preferred Date:</strong> $preferred_date</p>
            <p><strong>Preferred Time:</strong> $preferred_time</p>
        ";

        // Send the email
        if ($mail->send()) {
            echo 'Message has been sent successfully!';
        } else {
            echo 'Message could not be sent.';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
