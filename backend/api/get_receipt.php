<?php
header("Content-Type: application/json");
include("../config/db.php");

if(!isset($_GET['donation_id'])){
    echo json_encode(["error"=>"Donation ID missing"]);
    exit;
}

$donation_id = intval($_GET['donation_id']);

$sql = "SELECT u.name, u.email, d.category, d.amount, d.status
        FROM donations d
        JOIN users u ON d.user_id = u.id
        WHERE d.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $donation_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    echo json_encode(["error"=>"No data found"]);
    exit;
}

$row = $result->fetch_assoc();
echo json_encode($row);
?>