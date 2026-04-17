<?php
session_start();
header("Content-Type: application/json");
include("../config/db.php");

$donation_id = intval($_GET['donation_id']);

$sql = "SELECT t.status, t.updated_at
        FROM tracking t
        WHERE t.donation_id = '$donation_id'
        ORDER BY t.id DESC";

$result = $conn->query($sql);

$tracking = [];

while($row = $result->fetch_assoc()){
    $tracking[] = $row;
}

echo json_encode($tracking);
?>
