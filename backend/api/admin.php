<?php
include("../config/db.php");

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(empty($email) || empty($password)){
    echo "<script>alert('All fields required'); window.location.href='../../frontend/admin.html';</script>";
    exit;
}

// Query admin
$sql = "SELECT * FROM users 
        WHERE email='$email' AND role='admin'";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){

    $admin = $result->fetch_assoc();

    // Plain password match
    if($password == $admin['password']){
  window.location.href = "admin-dashboard.html";
        // Optional: session start
        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        // Redirect
       
        exit;

    } else {
        echo "<script>alert('Wrong password'); window.location.href='../../frontend/admin.html';</script>";
    }

} else {
    echo "<script>alert('Admin not found'); window.location.href='../../frontend/admin.html';</script>";
}
?>
