<?php
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["status"=>"error","message"=>"No data received"]);
    exit;
}

$name = $conn->real_escape_string($data['name'] ?? '');
$email = $conn->real_escape_string($data['email'] ?? '');
$password = $data['password'] ?? '';

if(empty($name) || empty($email) || empty($password)){
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

// Check existing user
$check = $conn->query("SELECT id FROM users WHERE email='$email'");
if($check && $check->num_rows > 0){
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit;
}

// Hash password (IMPORTANT 🔐)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert
$sql = "INSERT INTO users (name,email,password)
        VALUES ('$name','$email','$hashed_password')";

if($conn->query($sql)){
    echo json_encode(["status"=>"success","message"=>"Registration successful"]);
}else{
    echo json_encode(["status"=>"error","message"=>$conn->error]);
}
?>
