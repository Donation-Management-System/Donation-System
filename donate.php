<?php
header("Content-Type: application/json");
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = intval($data['user_id']);
$category = $conn->real_escape_string($data['category']);
$amount = floatval($data['amount']);
$payment = $conn->real_escape_string($data['payment_method']);

$sql = "INSERT INTO donations (user_id,category,amount,payment_method)
VALUES ('$user_id','$category','$amount','$payment')";

if($conn->query($sql)){
    $donation_id = $conn->insert_id;

    // insert tracking
    $conn->query("INSERT INTO tracking (donation_id,status) VALUES ('$donation_id','Pending')");

    echo json_encode([
        "message"=>"Donation successful",
        "donation_id"=>$donation_id
    ]);
}else{
    echo json_encode(["error"=>"Donation failed"]);
}
?>
