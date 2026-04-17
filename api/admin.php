<?php
header("Content-Type: application/json");
include("../config/db.php");

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Check if data exists
if(!$data){
    echo json_encode(["error"=>"No data received"]);
    exit;
}

// Sanitize input
$email = $conn->real_escape_string($data['email'] ?? '');
$password = $data['password'] ?? '';

// Validate input
if(empty($email) || empty($password)){
    echo json_encode(["error"=>"All fields required"]);
    exit;
}

// ✅ Use prepared statement (SECURE)
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if($result && $result->num_rows > 0){

    $admin = $result->fetch_assoc();

    // ✅ Correct password check
    if(password_verify($password, $admin['password'])){
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

$stmt->close();
$conn->close();
?>
