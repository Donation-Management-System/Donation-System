<?php
header("Content-Type: application/json");
include("../config/db.php");

$user_id = $_GET['user_id'] ?? '';

if(empty($user_id)){
    echo json_encode(["error"=>"No user ID"]);
    exit;
}

// Get user + donations
$sql = "SELECT u.name, d.id, d.category, d.amount 
        FROM users u
        LEFT JOIN donations d ON u.id = d.user_id
        WHERE u.id = '$user_id'
        ORDER BY d.id DESC";

$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
?>