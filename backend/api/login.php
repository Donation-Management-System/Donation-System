<?php
session_start();

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
    echo json_encode(["status"=>"error","message"=>"DB connection failed: ".$conn->connect_error]);
    exit;
}

// Handle all content types: JSON, FormData, URL-encoded
$email = $password = '';

$raw = file_get_contents("php://input");
if(!empty($raw)){
    // Try JSON
    $json = json_decode($raw, true);
    if($json){
        $email    = $json['email']    ?? '';
        $password = $json['password'] ?? '';
    } else {
        // Try URL-encoded (application/x-www-form-urlencoded)
        parse_str($raw, $parsed);
        $email    = $parsed['email']    ?? '';
        $password = $parsed['password'] ?? '';
    }
}

// Fallback to $_POST
if(empty($email)) $email    = $_POST['email']    ?? '';
if(empty($password)) $password = $_POST['password'] ?? '';

$email    = trim($conn->real_escape_string($email));
$password = trim($password);

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

if(password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    echo json_encode([
        "status"  => "success",
        "message" => "Login successful",
        "user_id" => $user['id'],
        "name"    => $user['name'],
        "role"    => $user['role'] ?? 'user'
    ]);
} else {
    echo json_encode(["status"=>"error","message"=>"Wrong password"]);
}
?>
