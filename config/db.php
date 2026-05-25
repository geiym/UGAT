<?php
// ============================================================
//   config/db.php  —  Dynamic Database Connection (MySQLi)
// ============================================================

// Railway automatically provides these variables in production.
// If they don't exist, it defaults to your local setup!
define('DB_HOST', $_ENV['MYSQLHOST']     ?? 'localhost');
define('DB_USER', $_ENV['MYSQLUSER']     ?? 'root');          
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');              
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'ugat_db');
define('DB_PORT', $_ENV['MYSQLPORT']     ?? 3306);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    // In production, log this and show a generic error page
    error_log('DB connection failed: ' . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection error.']));
}

// Force UTF-8
$conn->set_charset('utf8mb4');
?>