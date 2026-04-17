<?php
header("Content-Type: application/json");
include("../config/db.php");

$sql = "SELECT d.id, u.name, d.category, d.amount, d.payment_method, d.status, d.created_at
        FROM donations d
        JOIN users u ON d.user_id = u.id
        ORDER BY d.id DESC";

$result = $conn->query($sql);

$donations = [];

while($row = $result->fetch_assoc()){
    $donations[] = $row;
}

echo json_encode($donations);
?>