<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$name    = strip_tags(trim($_POST['name'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = strip_tags(trim($_POST['subject'] ?? 'No Subject'));
$message = trim($_POST['message'] ?? '');
$phone   = strip_tags(trim($_POST['phone'] ?? ''));

if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields correctly.']);
    exit;
}

$to = "info@amatshawefunerals.co.za";
$email_subject = "New Contact Message: $subject";
$email_body = "You have received a new message from your website contact form:\n\n" .
              "Name: $name\n" .
              "Email: $email\n" .
              "Phone: $phone\n" .
              "Subject: $subject\n\n" .
              "Message:\n$message\n";

$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

if (mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Oops! Something went wrong and we couldn’t send your message.']);
}
?>
