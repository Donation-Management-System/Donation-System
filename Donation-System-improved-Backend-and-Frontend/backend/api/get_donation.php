<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$sql = "SELECT d.id, u.name, d.category, d.amount, d.payment_method, d.status, d.created_at
        FROM donations d
        JOIN users u ON d.user_id = u.id
        ORDER BY d.id DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to fetch donations"]);
    exit;
}

$donations = [];

while ($row = $result->fetch_assoc()) {
    $donations[] = $row;
}

echo json_encode(["success" => true, "data" => $donations]);

$conn->close();
