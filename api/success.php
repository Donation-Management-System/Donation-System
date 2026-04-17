<?php
session_start();
$id = $_GET['donation_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Success</title>

<style>
body {
    font-family: Arial;
    text-align: center;
    padding-top: 100px;
    background: #f5f5f5;
}
.box {
    background: white;
    padding: 30px;
    margin: auto;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
button {
    padding: 10px 20px;
    margin-top: 15px;
    border: none;
    background: green;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}
</style>

<script>
// ✅ FIXED: was "../../frontend/donor-dashboard.html" — broken relative path
setTimeout(() => {
    window.location.href = "https://donation-system.great-site.net/donor-dashboard.html";
}, 5000);
</script>

</head>
<body>

<div class="box">
    <h2>✅ Payment Successful!</h2>
    <h1>Thank you for your donation!</h1>
    <p>Redirecting to dashboard in 5 seconds...</p>

    <a href="download_receipt.php?id=<?php echo intval($id); ?>" target="_blank">
        <button>Download Receipt</button>
    </a>
</div>

</body>
</html>