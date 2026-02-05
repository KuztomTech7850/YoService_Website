<?php
// contact-submit.php

// Basic security: only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Simple helper to safely grab a field
function field($name) {
    return isset($_POST[$name]) ? trim($_POST[$name]) : '';
}

$name    = field('name');
$email   = field('email');
$message = field('message');
$source  = field('source_page');

// Very basic validation
if ($name === '' || $email === '' || $message === '') {
    header('Location: /?contact_status=error');
    exit;
}

// TODO: replace with your actual Zoho SMTP config if you want real SMTP.
// For now, use the server's mail() so we can verify basic delivery.
// Later we can switch this to PHPMailer + Zoho SMTP for more reliability.

$to      = 'contact@yoservice.work';
$subject = 'New contact form message from YoService';
$body    = "Name: {$name}\n"
         . "Email: {$email}\n"
         . "From page: {$source}\n\n"
         . "Message:\n{$message}\n";

$headers = "From: YoService Website <contact@yoservice.work>\r\n";
$headers .= "Reply-To: {$email}\r\n";

$success = mail($to, $subject, $body, $headers);

if ($success) {
    // Redirect back to home with a success flag (we can read this in JS or show a small banner)
    header('Location: /?contact_status=success');
} else {
    header('Location: /?contact_status=error');
}
exit;
