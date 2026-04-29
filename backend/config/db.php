<?php
// ================================================
// db.php — Database connection using .env config
// ================================================
require_once __DIR__ . '/config.php';

$host   = getenv('DB_HOST')   ?: 'localhost';
$user   = getenv('DB_USER')   ?: '';
$pass   = getenv('DB_PASS')   ?: '';
$dbname = getenv('DB_NAME')   ?: '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed."]));
}

$conn->set_charset("utf8mb4");
?>
