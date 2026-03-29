<?php
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

// DEBUG
if(!$data){
    echo json_encode(["error"=>"No data received"]);
    exit;
}

$name = $conn->real_escape_string($data['name'] ?? '');
$email = $conn->real_escape_string($data['email'] ?? '');
$password = $conn->real_escape_string($data['password'] ?? '');

// DEBUG CHECK
if(empty($password)){
    echo json_encode([
        "error"=>"Password is empty",
        "received_data"=>$data
    ]);
    exit;
}

// Check existing user
$check = $conn->query("SELECT id FROM users WHERE email='$email'");
if($check && $check->num_rows > 0){
    echo json_encode(["error"=>"Email already exists"]);
    exit;
}

// INSERT
$sql = "INSERT INTO users (name,email,password)
        VALUES ('$name','$email','$password')";

if($conn->query($sql)){
    echo json_encode(["message"=>"Registration successful"]);
}else{
    echo json_encode([
        "error"=>"DB Error",
        "debug"=>$conn->error
    ]);
}
?>
