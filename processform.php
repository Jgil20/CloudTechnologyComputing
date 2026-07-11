<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function ctc_contact_fail(int $status, string $message): never
{
    http_response_code($status);
    header('Content-Type: text/plain; charset=utf-8');
    echo $message;
    exit;
}

function ctc_contact_truncate(string $value, int $maxLength): string
{
    return function_exists('mb_substr')
        ? mb_substr($value, 0, $maxLength)
        : substr($value, 0, $maxLength);
}

function ctc_contact_value(string $key, int $maxLength): string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    $value = str_replace(["\r", "\n"], ' ', $value);
    return ctc_contact_truncate($value, $maxLength);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    ctc_contact_fail(405, 'Only POST requests are allowed.');
}

$postedToken = (string) ($_POST['csrf'] ?? '');
if (empty($_SESSION['csrf']) || $postedToken === '' || !hash_equals((string) $_SESSION['csrf'], $postedToken)) {
    ctc_contact_fail(403, 'Invalid request token. Reload the form and try again.');
}

// Honeypot: return a normal response without sending mail.
if (!empty($_POST['website'] ?? '')) {
    http_response_code(204);
    exit;
}

$lastSubmit = (int) ($_SESSION['last_contact_submit'] ?? 0);
if ($lastSubmit > 0 && (time() - $lastSubmit) < 10) {
    ctc_contact_fail(429, 'Please wait a few seconds before submitting another message.');
}

$firstName = ctc_contact_value('fname', 80);
$lastName = ctc_contact_value('lname', 80);
$company = ctc_contact_value('company', 140);
$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_SANITIZE_EMAIL);
$phone = ctc_contact_value('phone', 40);
$message = trim((string) ($_POST['message'] ?? ''));
$message = ctc_contact_truncate($message, 5000);

if ($firstName === '' || $lastName === '' || $company === '' || $email === '' || $phone === '' || $message === '') {
    ctc_contact_fail(400, 'Please complete every required field.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ctc_contact_fail(400, 'Enter a valid email address.');
}

if (!preg_match('/^[+0-9()\-\.\s]{7,40}$/', $phone)) {
    ctc_contact_fail(400, 'Enter a valid phone number.');
}

$recipient = 'Jhongil@cloudtechnologycomputing.com';
$fullName = trim($firstName . ' ' . $lastName);
$subject = 'New website consultation request from ' . $fullName;
$body = implode("\n", [
    'New consultation request',
    '------------------------',
    'Name: ' . $fullName,
    'Company: ' . $company,
    'Email: ' . $email,
    'Phone: ' . $phone,
    '',
    'Message:',
    $message,
]);

// Use the website domain in From for SPF/DMARC alignment; use Reply-To for the visitor.
$headers = implode("\r\n", [
    'From: Cloud Technology Computing Website <no-reply@cloudtechnologycomputing.com>',
    'Reply-To: ' . $email,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . PHP_VERSION,
]);

if (!mail($recipient, $subject, $body, $headers)) {
    error_log('Contact form mail() failed for ' . $email);
    ctc_contact_fail(500, 'Your message could not be sent. Please email Jgil20@me.com directly.');
}

$_SESSION['last_contact_submit'] = time();
unset($_SESSION['csrf']);

$returnTo = (string) ($_POST['return_to'] ?? '/contact.php?sent=1');
if (!str_starts_with($returnTo, '/') || str_starts_with($returnTo, '//') || preg_match('/[\r\n]/', $returnTo)) {
    $returnTo = '/contact.php?sent=1';
}

header('Location: ' . $returnTo, true, 303);
exit;
