<?php
header('Content-Type: text/plain; charset=UTF-8');

function respond($status, $message)
{
    http_response_code($status);
    echo $message;
    exit;
}

function post_value($key)
{
    return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, 'Method not allowed.');
}

$name = post_value('name');
$email = post_value('email');
$subject = post_value('subject');
$message = post_value('message');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    respond(400, 'Please complete all fields.');
}

if (strlen($name) > 200 || strlen($subject) > 200 || strlen($message) > 10000) {
    respond(400, 'One or more fields are too long.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(400, 'Please enter a valid email address.');
}

$receivingEmail = 'bonsusamuelkwadwo@gmail.com';
$mailSubject = 'New message from your portfolio website';
$mailBody = "Name: {$name}\r\nEmail: {$email}\r\nSubject: {$subject}\r\n\r\n{$message}";
$headers = array(
    'From: Samuel Kwadwo Bonsu Portfolio <' . $receivingEmail . '>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
);

if (!function_exists('mail') || !@mail($receivingEmail, $mailSubject, $mailBody, implode("\r\n", $headers))) {
    respond(500, 'The message could not be sent. Please try again later or email bonsusamuelkwadwo@gmail.com directly.');
}

echo 'OK';