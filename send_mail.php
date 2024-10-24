<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = "helloitsmejatin@gmail.com";
    $message = htmlspecialchars(trim($_POST['message']));

    $to = htmlspecialchars(trim($_GET['to_email']));

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid recipient email format.'); window.history.back();</script>";
        exit;
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid sender email format.'); window.history.back();</script>";
        exit;
    }


    $subject = "New Contact Form Submission from $firstName $lastName";


    $body = "
        <h2>Contact Request</h2>
        <p><strong>Name:</strong> $firstName $lastName</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
    ";


    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $firstName $lastName <$email>" . "\r\n";


    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.history.back(); </script>";
    } else {
        echo "<script>alert('Failed to send message.');  </script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
