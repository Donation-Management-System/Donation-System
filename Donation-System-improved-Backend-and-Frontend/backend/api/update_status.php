<?php
header("Content-Type: application/json; charset=utf-8");
include_once "../config/db.php";

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

// Validate inputs
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid donation ID"]);
    exit;
}

// Allowed status values
$allowedStatuses = ['pending', 'accepted', 'rejected'];

if (empty($status) || !in_array($status, $allowedStatuses)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid status. Allowed values: " . implode(', ', $allowedStatuses)]);
    exit;
}

// Update donation status using prepared statement
$sql = "UPDATE donations SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("si", $status, $id);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to update status"]);
    $stmt->close();
    exit;
}

$affectedRows = $stmt->affected_rows;
$stmt->close();

if ($affectedRows === 0) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Donation not found"]);
} else {
    echo json_encode([
        "success" => true,
        "message" => "Status updated successfully",
        "data" => [
            "id" => $id,
            "status" => $status,
            "affected_rows" => $affectedRows
        ]
    ]);
}

$conn->close();
