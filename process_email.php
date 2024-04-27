<?php

//require 'mail.php';
use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'Exception.php';
  require 'PHPMailer.php';
  require 'SMTP.php';

// Check if email is provided
if(isset($_POST['email'])) {
    $email = $_POST['email'];
     
    
    // Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "committee";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists in the database
    $sql = "SELECT * FROM committees WHERE email='$email'";
    $result = $conn->query($sql);
    $conveynorname = "SELECT convenerName FROM committees WHERE email='$email'";
    if ($result->num_rows > 0) {
        // Email exists, generate verification link and send email
        $verification_link = "http://localhost/php/abc.php?email=" . urlencode($email); // Change this URL to your verification page
        $message = "Please click the following link to verify your email address: " . $verification_link;
        $subject = "Email Verification";
        $headers = "From: anandi.ket@somaiya.edu\r\n"; // Replace with your Gmail address
        
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->SMTPDebug  = 0;  
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        //$mail->Host       = "smtp.mail.yahoo.com";
        $mail->Username   = "anandi.ket@somaiya.edu";
        $mail->Password   = "kfzlmfuljujfyiuj";

        $mail->IsHTML(true);
        $mail->AddAddress($email, "$email");
        $mail->SetFrom("anandi.ket@somaiya.edu", "Club Connect");
        //$mail->AddReplyTo("reply-to-email", "reply-to-name");
        //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
        $mail->Subject = $subject;
        $content = $message;

        $mail->MsgHTML($content); 
        if(!$mail->Send()) {
            header("Location: signup.php?error=Error while sending Email.");
            //echo "<pre>";
            //var_dump($mail);
            return false;
        } else {
            header("Location: signup.php?error=Email sent successfully");
            return true;
        }
        // Send email
        if($mail->Send()) {
        echo "Email sent successfully to $email";
        } else {
        header("Location: signup.php?error=Failed to send an email");
        exit();
         }  
} else {
        // Email does not exist in the database
        header("Location: signup.php?error=Email address not found");
        exit();
    }

    $conn->close();
} else {
    // Redirect if email is not provided
    header("Location: signup.php?error=Email address is required");
    exit();
}


?>
