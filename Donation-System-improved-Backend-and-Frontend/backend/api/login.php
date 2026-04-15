<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Email and password required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format"]);
    exit;
}

$sql = "SELECT id, name, password, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "User not found"]);
    $stmt->close();
    exit;
}

$user = $result->fetch_assoc();

// Note: Passwords should be hashed; this assumes plain text for compatibility
if ($password === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "data" => [
            "user_id" => $user['id'],
            "name" => $user['name'],
            "role" => $user['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Wrong password"]);
}

$stmt->close();
$conn->close();
