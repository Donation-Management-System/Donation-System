<?php
include("../config/db.php");

$id = $_GET['id'];

// get image name first
$get = $conn->query("SELECT image FROM student_images WHERE id=$id");
$row = $get->fetch_assoc();

$image = $row['image'];

// delete file
unlink("../uploads/" . $image);

// delete from db
$conn->query("DELETE FROM student_images WHERE id=$id");

echo json_encode(["status"=>"deleted"]);
?>