<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id) || !ctype_digit($user_id)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid or missing user_id"]);
    exit;
}

$sql = "SELECT u.name, d.id, d.category, d.amount
        FROM users u
        LEFT JOIN donations d ON u.id = d.user_id
        WHERE u.id = ?
        ORDER BY d.id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to fetch donations"]);
    $stmt->close();
    $conn->close();
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["success" => true, "data" => $data]);

$stmt->close();
$conn->close();
