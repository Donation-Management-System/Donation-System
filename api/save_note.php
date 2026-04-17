<?php
include("../config/db.php");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    http_response_code(200);
    exit;
}

// InfinityFree sometimes blocks php://input — try both methods
$data = json_decode(file_get_contents("php://input"), true);

// Fallback to $_POST if php://input empty
if(empty($data)){
    $data = $_POST;
}

$category = isset($data['category']) ? $conn->real_escape_string($data['category']) : '';
$note     = isset($data['note'])     ? $conn->real_escape_string($data['note'])     : '';

if(empty($category)){
    echo json_encode(["status"=>"error", "msg"=>"Category required"]);
    exit;
}

// Make sure table exists first
$conn->query("CREATE TABLE IF NOT EXISTS category_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) UNIQUE NOT NULL,
    note TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$check = $conn->query("SELECT id FROM category_notes WHERE category='$category'");
if($check && $check->num_rows > 0){
    $conn->query("UPDATE category_notes SET note='$note' WHERE category='$category'");
} else {
    $conn->query("INSERT INTO category_notes (category, note) VALUES ('$category', '$note')");
}

if($conn->errno){
    echo json_encode(["status"=>"error", "msg"=>$conn->error]);
} else {
    echo json_encode(["status"=>"success"]);
}
?>
