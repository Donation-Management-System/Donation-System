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

$conn->query("ALTER TABLE student_images ADD COLUMN IF NOT EXISTS category VARCHAR(50) DEFAULT 'student'");

if(!isset($_FILES['image']) || !isset($_POST['category'])){
    echo json_encode(["status"=>"error", "msg"=>"Missing file or category"]);
    exit;
}

$category = $conn->real_escape_string($_POST['category']);
$image    = basename($_FILES['image']['name']);
$tmp      = $_FILES['image']['tmp_name'];
$folder   = "../uploads/" . $image;

if(move_uploaded_file($tmp, $folder)){
    $sql = "INSERT INTO student_images (image, category) VALUES ('$image', '$category')";
    if($conn->query($sql)){
        echo json_encode(["status"=>"success"]);
    } else {
        echo json_encode(["status"=>"error", "msg"=>$conn->error]);
    }
} else {
    echo json_encode(["status"=>"error", "msg"=>"File move failed. Check uploads folder permissions."]);
}
?>
