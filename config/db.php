<?php
// Use a more explicit approach to capture environment variables
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: 'ugat_db';
$port = (int)(getenv('MYSQLPORT') ?: 3306);

// Create connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    // DEVELOPMENT DEBUG: Uncomment the line below to see the error on your screen
    // die("Connection failed: " . $conn->connect_error); 
    
    // PRODUCTION: Keep this for the live site
    error_log('DB connection failed: ' . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection error. Check your environment variables.']));
}

// Force UTF-8
$conn->set_charset('utf8mb4');
?>