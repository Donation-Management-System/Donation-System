<?php
include("../config/db.php");

$id = $_POST['id'];
$status = $_POST['status'];

$conn->query("UPDATE donations SET status='$status' WHERE id=$id");

echo "updated";
?>
