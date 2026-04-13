<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "donation_db";
$conn = new mysqli("hostname", "username", "password", "dbname");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
