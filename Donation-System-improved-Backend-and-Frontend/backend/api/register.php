<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Name, email, and password are required"]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format"]);
    exit;
}

// Validate password strength (at least 6 characters)
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters"]);
    exit;
}

// Check if email already exists
$checkSql = "SELECT id FROM users WHERE email = ?";
$checkStmt = $conn->prepare($checkSql);

if (!$checkStmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult && $checkResult->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Email already exists"]);
    $checkStmt->close();
    exit;
}

$checkStmt->close();

// Insert new user with prepared statement
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$role = "donor"; // Default role
$stmt->bind_param("ssss", $name, $email, $password, $role);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Registration failed"]);
    $stmt->close();
    exit;
}

$stmt->close();

http_response_code(201);
echo json_encode([
    "success" => true,
    "message" => "Registration successful",
    "data" => ["email" => $email]
]);

$conn->close();
