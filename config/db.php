<?php
define('DB_HOST', $_ENV['MYSQLHOST']     ?? 'localhost');
define('DB_USER', $_ENV['MYSQLUSER']     ?? 'root');
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'ugat_db');
define('DB_PORT', (int)($_ENV['MYSQLPORT'] ?? 3306));

if (!extension_loaded('mysqli')) {
    error_log('mysqli not loaded. Loaded extensions: ' . implode(', ', get_loaded_extensions()));
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'mysqli not available. Extensions: ' . implode(', ', get_loaded_extensions())]));
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    error_log('DB connection failed: ' . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection error.']));
}

$conn->set_charset('utf8mb4');
?>