<?php
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if(empty($email) || empty($password)){
    echo json_encode(["error"=>"All fields required"]);
    exit;
}

$sql = "SELECT * FROM users WHERE email='$email' AND role='admin'";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){

    $admin = $result->fetch_assoc();

    if($password == $admin['password']){
        echo json_encode([
            "message"=>"Login success",
            "name"=>$admin['name']
        ]);
    } else {
        echo json_encode(["error"=>"Wrong password"]);
    }

} else {
    echo json_encode(["error"=>"Admin not found"]);
}
?>
