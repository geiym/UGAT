<?php
// ============================================================
//   config/db.php  —  Dynamic Database Connection (MySQLi)
// ============================================================

// getenv() reads directly from system environment memory securely.
// If they don't exist, it safely defaults to your local setup!
define('DB_HOST', getenv('MYSQLHOST')     ?: 'localhost');
define('DB_USER', getenv('MYSQLUSER')     ?: 'root');          
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');              
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'ugat_db');
define('DB_PORT', getenv('MYSQLPORT')     ?: 3306);

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);

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