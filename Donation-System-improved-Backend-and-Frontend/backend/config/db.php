<?php
// Database configuration using environment variables or defaults
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'donation_db';

// Create mysqli connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection error"]);
    exit;
}

// Set charset to utf8mb4 for full Unicode support
if (!$conn->set_charset("utf8mb4")) {
    error_log("Failed to set charset: " . $conn->error);
}
