<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

$host = ctc_env('DB_HOST', '127.0.0.1');
$port = ctc_env('DB_PORT', '3306');
$dbname = ctc_env('DB_NAME', '');
$username = ctc_env('DB_USERNAME', '');
$password = ctc_env('DB_PASSWORD', '');

if ($dbname === '' || $username === '' || $password === '') {
    error_log('Database credentials are not configured. Set DB_NAME, DB_USERNAME, and DB_PASSWORD.');
    http_response_code(500);
    exit('Database connection is not configured.');
}

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection error.');
}
