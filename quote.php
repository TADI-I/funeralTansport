<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize form data
$name = strip_tags(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone = strip_tags(trim($_POST['phone'] ?? ''));
$service = strip_tags(trim($_POST['service'] ?? ''));
$details = strip_tags(trim($_POST['details'] ?? ''));

// Validate required fields
if (empty($name) || empty($phone) || empty($service) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields correctly.']);
    exit;
}

// Service options mapping for better display
$serviceOptions = [
    'domestic_repatriation' => 'Domestic Repatriation',
    'international_repatriation' => 'International Repatriation',
    'cross_border_transport' => 'Cross-Border Transport',
    'funeral_services' => 'Funeral Services',
    'funeral_cover' => 'Funeral Cover',
    'other' => 'Other Services'
];

$serviceDisplay = $serviceOptions[$service] ?? $service;

// Email configuration
$to = "info@amatshawefunerals.co.za";
$subject = "New Quote Request: $serviceDisplay";
$email_body = "You have received a new quote request from your website:\n\n" .
              "Name: $name\n" .
              "Email: $email\n" .
              "Phone: $phone\n" .
              "Service Needed: $serviceDisplay\n\n" .
              "Additional Details:\n" . ($details ? $details : "No additional details provided") . "\n";

$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// Send email
if (mail($to, $subject, $email_body, $headers)) {
    echo json_encode(['status' => 'success', 'message' => 'Your quote request has been received. We\'ll contact you shortly!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Oops! Something went wrong and we couldn\'t process your request.']);
}
?>