<?php
require_once __DIR__ . '/includes/env.php';

// Site Settings
$siteName = 'Cloud Technology Computing';
$siteEmail = 'jhongil@cloudtechnologycomputing.com';

// Database configuration from environment variables.
define('DB_HOST', ctc_env('DB_HOST', '127.0.0.1'));
define('DB_USERNAME', ctc_env('DB_USERNAME', ''));
define('DB_PASSWORD', ctc_env('DB_PASSWORD', ''));
define('DB_NAME', ctc_env('DB_NAME', ''));
define('DB_PORT', ctc_env('DB_PORT', '3306'));

$siteURL = rtrim(ctc_env('APP_URL', 'https://www.cloudtechnologycomputing.com'), '/') . '/';
?>
