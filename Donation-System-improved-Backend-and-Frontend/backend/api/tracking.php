<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$donation_id = isset($_GET['donation_id']) ? intval($_GET['donation_id']) : 0;

if ($donation_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid or missing donation_id"]);
    exit;
}

$sql = "SELECT t.status, t.updated_at
        FROM tracking t
        WHERE t.donation_id = ?
        ORDER BY t.id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("i", $donation_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to fetch tracking data"]);
    $stmt->close();
    exit;
}

$tracking = [];

while ($row = $result->fetch_assoc()) {
    $tracking[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $tracking
]);

$stmt->close();
$conn->close();
