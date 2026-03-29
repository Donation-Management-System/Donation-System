<?php
session_start();
header("Content-Type: application/json");
include("../config/db.php");

// Get JSON
$data = json_decode(file_get_contents("php://input"), true);

// Check data
if(!$data){
    echo json_encode(["error"=>"No data received"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

// Validation
if(empty($email) || empty($password)){
    echo json_encode(["error"=>"Email and password required"]);
    exit;
}

// Find user
$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if(!$result || $result->num_rows == 0){
    echo json_encode(["error"=>"User not found"]);
    exit;
}

$user = $result->fetch_assoc();

// Check password
if($password == $user['password']){
    echo json_encode([
        "message"=>"Login successful",
        "user_id"=>$user['id'],
        "name"=>$user['name'],
        "role"=>$user['role']
    ]);
}else{
    echo json_encode([
        "error"=>"Wrong password",
        "debug_db_pass"=>$user['password']
    ]);
}
?>
