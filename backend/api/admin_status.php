<?php
header("Content-Type: application/json");
include("../config/db.php");

// Total
$total = $conn->query("SELECT COUNT(*) as total FROM donations")->fetch_assoc()['total'];

// Pending
$pending = $conn->query("SELECT COUNT(*) as pending FROM donations WHERE status='pending'")->fetch_assoc()['pending'];

// Accepted
$accepted = $conn->query("SELECT COUNT(*) as accepted FROM donations WHERE status='accepted'")->fetch_assoc()['accepted'];

echo json_encode([
    "total"=>$total,
    "pending"=>$pending,
    "accepted"=>$accepted
]);
?>
