<?php
include("../config/db.php");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Auto-create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS category_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) UNIQUE NOT NULL,
    note TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$category = $conn->real_escape_string($_GET['category'] ?? '');

$result = $conn->query("SELECT note FROM category_notes WHERE category='$category'");
if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo json_encode(["note" => $row['note']]);
} else {
    echo json_encode(["note" => ""]);
}
?>
