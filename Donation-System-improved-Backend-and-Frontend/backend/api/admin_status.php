<?php
include_once "../config/db.php";

header("Content-Type: application/json; charset=utf-8");

const DATABASE_ERROR = "Database error";

// Total
$totalResult = $conn->query("SELECT COUNT(*) as total FROM donations");
if (!$totalResult) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => DATABASE_ERROR]);
    exit;
}
$total = $totalResult->fetch_assoc()['total'];

// Pending
$pendingResult = $conn->query("SELECT COUNT(*) as pending FROM donations WHERE status='pending'");
if (!$pendingResult) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => DATABASE_ERROR]);
    exit;
}
$pending = $pendingResult->fetch_assoc()['pending'];

// Accepted
$acceptedResult = $conn->query("SELECT COUNT(*) as accepted FROM donations WHERE status='accepted'");
if (!$acceptedResult) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => DATABASE_ERROR]);
    exit;
}
$accepted = $acceptedResult->fetch_assoc()['accepted'];

echo json_encode([
    "success" => true,
    "data" => [
        "total" => $total,
        "pending" => $pending,
        "accepted" => $accepted
    ]
]);

$conn->close();
