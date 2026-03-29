<?php
session_start();
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["status"=>"error","message"=>"No data received"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

if(empty($email) || empty($password)){
    echo json_encode(["status"=>"error","message"=>"Email and password required"]);
    exit;
}

$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if(!$result || $result->num_rows == 0){
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit;
}

$user = $result->fetch_assoc();


if($password == $user['password']){

    $_SESSION['user_id'] = $user['id'];

    echo json_encode([
        "status"=>"success",
        "message"=>"Login successful",
        "user_id"=>$user['id'],
        "name"=>$user['name'],
        "role"=>$user['role']
    ]);

}else{
    echo json_encode([
        "status"=>"error",
        "message"=>"Wrong password"
    ]);
}
?>
