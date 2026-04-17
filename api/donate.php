<?php
session_start();
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

// Validate input (important to avoid hidden bugs)
if (!$data || !isset($data['user_id'], $data['category'], $data['amount'], $data['payment_method'])) {
    echo json_encode([
        "error" => "Invalid or missing input"
    ]);
    exit;
}

$user_id = intval($data['user_id']);
$category = $conn->real_escape_string($data['category']);
$amount = floatval($data['amount']);
$payment = $conn->real_escape_string($data['payment_method']);

// ✅ Define status ONCE
$status = "Pending";

// ✅ Insert into donations WITH status
$sql = "INSERT INTO donations (user_id, category, amount, payment_method, status)
VALUES ('$user_id', '$category', '$amount', '$payment', '$status')";

if ($conn->query($sql)) {
    $donation_id = $conn->insert_id;

    // ✅ Keep tracking table in sync
    $conn->query("INSERT INTO tracking (donation_id, status) VALUES ('$donation_id', '$status')");

    echo json_encode([
        "message" => "Donation successful",
        "donation_id" => $donation_id,
        "status" => $status
    ]);
} else {
    echo json_encode([
        "error" => $conn->error
    ]);
}
?>