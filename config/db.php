<?php
$host = "sql213.epizy.com";
$user = "if0_41646038";
$pass = "donation0011";
$dbname = "if0_41646038_donation_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
