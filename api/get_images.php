<?php
include("../config/db.php");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Auto-create table with category column if not exists
$conn->query("CREATE TABLE IF NOT EXISTS student_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Add category column if it doesn't exist (safe migration)
$conn->query("ALTER TABLE student_images ADD COLUMN IF NOT EXISTS category VARCHAR(50) DEFAULT 'student'");

$category = $conn->real_escape_string($_GET['category'] ?? '');

$result = $conn->query("SELECT * FROM student_images WHERE category='$category' ORDER BY id DESC");

$data = [];
if($result){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}
echo json_encode($data);
?>
