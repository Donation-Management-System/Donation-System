<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$donation_id = $_GET['donation_id'] ?? '';

if (empty($donation_id) || !ctype_digit($donation_id)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid or missing donation_id"]);
    exit;
}

$sql = "SELECT d.id, d.amount, d.category, d.payment_method, d.status, d.created_at, u.name, u.email
        FROM donations d
        JOIN users u ON d.user_id = u.id
        WHERE d.id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $row]);
} else {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Donation not found"]);
}

$stmt->close();
$conn->close();
