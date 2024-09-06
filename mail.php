<?php
// Configuration
$to_email = 'akshay.8bittech@gmail.com'; // recipient email address
$subject = 'Form Submission'; // email subject

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $address = $_POST["address"];
    $test = $_POST["test"];
    $upload = $_FILES["upload"];
    $preferred_date = $_POST["date"];
    $preferred_time = $_POST["time"];

    // Get selected checkboxes
    $options = array();
    if (isset($_POST["optionsRadios"])) {
        foreach ($_POST["optionsRadios"] as $option) {
            $options[] = $option;
        }
    }

    // Create email message
    $message = "First Name: $first_name\n";
    $message .= "Last Name: $last_name\n";
    $message .= "Email: $email\n";
    $message .= "Mobile: $mobile\n";
    $message .= "Address: $address\n";
    $message .= "Test: $test\n";
    $message .= "Upload: " . $upload["name"] . "\n";
    $message .= "Preferred Date: $preferred_date\n";
    $message .= "Preferred Time: $preferred_time\n";
    $message .= "Options: " . implode(", ", $options) . "\n";

    // Send email
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    mail($to_email, $subject, $message, $headers);

    // Upload file to server (optional)
    if ($upload["size"] > 0) {
        $upload_dir = 'uploads/'; // upload directory
        $upload_file = $upload_dir . $upload["name"];
        move_uploaded_file($upload["tmp_name"], $upload_file);
    }
}

?>