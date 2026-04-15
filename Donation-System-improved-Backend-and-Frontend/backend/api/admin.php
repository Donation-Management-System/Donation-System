<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format"]);
    exit;
}

$sql = "SELECT id, name, password FROM users WHERE email = ? AND role = 'admin'";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    // Note: Passwords should be hashed; this assumes plain text for compatibility
    if ($password === $admin['password']) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['role'] = 'admin';

        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "data" => ["name" => $admin['name']]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Wrong password"]);
    }
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Admin not found"]);
}

$stmt->close();
$conn->close();
