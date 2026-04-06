<?php
// includes/db.php
// Centralized DB connection using mysqli. Load sensitive values from environment if available.

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_PORT = getenv('DB_PORT') ?: '3306';
$DB_USER = getenv('DB_USER') ?: 'u249000411_Jhongil';
$DB_PASS = getenv('DB_PASS') ?: 'Spiderman8085$';
$DB_NAME = getenv('DB_NAME') ?: 'u249000411_CloudHoneyPot';
 
$mysqli = @new mysqli("$DB_HOST:$DB_PORT", $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    error_log("DB connection failed: " . $mysqli->connect_error);
    die("Temporary database issue. Please try again later.");
}

$mysqli->set_charset('utf8mb4');
?>
