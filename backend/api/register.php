<?php
if(isset($_SERVER['HTTP_ORIGIN'])){
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: *");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){ http_response_code(200); exit; }

include("../config/db.php");

if($conn->connect_error){
    echo json_encode(["status"=>"error","message"=>"DB connection failed"]);
    exit;
}

$name = $email = $password = '';

$raw = file_get_contents("php://input");
if(!empty($raw)){
    $json = json_decode($raw, true);
    if($json){
        $name     = $json['name']     ?? '';
        $email    = $json['email']    ?? '';
        $password = $json['password'] ?? '';
    } else {
        parse_str($raw, $parsed);
        $name     = $parsed['name']     ?? '';
        $email    = $parsed['email']    ?? '';
        $password = $parsed['password'] ?? '';
    }
}

if(empty($name)) $name         = $_POST['name']     ?? '';
if(empty($email)) $email       = $_POST['email']    ?? '';
if(empty($password)) $password = $_POST['password'] ?? '';

$name     = trim($conn->real_escape_string($name));
$email    = trim($conn->real_escape_string($email));
$password = trim($password);

if(empty($name) || empty($email) || empty($password)){
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

$check = $conn->query("SELECT id FROM users WHERE email='$email'");
if($check && $check->num_rows > 0){
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

if($conn->query("INSERT INTO users (name,email,password) VALUES ('$name','$email','$hashed')")){
    echo json_encode(["status"=>"success","message"=>"Registration successful"]);
} else {
    echo json_encode(["status"=>"error","message"=>$conn->error]);
}
?>
