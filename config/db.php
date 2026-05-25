<?php
// Force the app to get variables from Railway.
// If any of these are empty, we want to know immediately.
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

// Debugging: If it fails, this will show exactly which variable is missing
if (!$host || !$user || !$pass || !$db) {
    die(json_encode(['success' => false, 'message' => "DB Config Missing: Host:$host, User:$user, DB:$db"]));
}

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$conn->set_charset('utf8mb4');
?>