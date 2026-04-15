<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['user_id']) || !isset($data['category']) || !isset($data['amount']) || !isset($data['payment_method'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$user_id = intval($data['user_id']);
$category = trim($data['category']);
$amount = floatval($data['amount']);
$payment = trim($data['payment_method']);

// Validate data types and ranges
if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid user_id"]);
    exit;
}

if (empty($category)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Category cannot be empty"]);
    exit;
}

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Amount must be greater than 0"]);
    exit;
}

if (empty($payment)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Payment method cannot be empty"]);
    exit;
}

// Insert donation using prepared statement
$sql = "INSERT INTO donations (user_id, category, amount, payment_method) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("isds", $user_id, $category, $amount, $payment);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to create donation"]);
    $stmt->close();
    exit;
}

$donation_id = $stmt->insert_id;
$stmt->close();

// Insert tracking record
$tracking_sql = "INSERT INTO tracking (donation_id, status) VALUES (?, ?)";
$tracking_stmt = $conn->prepare($tracking_sql);

if (!$tracking_stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$status = "Pending";
$tracking_stmt->bind_param("is", $donation_id, $status);

if (!$tracking_stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to create tracking record"]);
    $tracking_stmt->close();
    exit;
}

$tracking_stmt->close();

http_response_code(201);
echo json_encode([
    "success" => true,
    "message" => "Donation successful",
    "data" => ["donation_id" => $donation_id]
]);

$conn->close();
