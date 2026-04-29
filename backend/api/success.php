<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$id       = intval($_GET['donation_id'] ?? 0);
$site_url = rtrim(getenv('SITE_URL'), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Success</title>
<style>
body { font-family: Arial; text-align: center; padding-top: 100px; background: #f5f5f5; }
.box { background: white; padding: 30px; margin: auto; width: 400px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
button { padding: 10px 20px; margin-top: 15px; border: none; background: green; color: white; cursor: pointer; border-radius: 5px; }
</style>
<script>
setTimeout(() => {
    window.location.href = "<?php echo htmlspecialchars($site_url); ?>/donor-dashboard.html";
}, 5000);
</script>
</head>
<body>
<div class="box">
    <h2>✅ Payment Successful!</h2>
    <h1>Thank you for your donation!</h1>
    <p>Redirecting to dashboard in 5 seconds...</p>
    <a href="download_receipt.php?id=<?php echo $id; ?>" target="_blank">
        <button>Download Receipt</button>
    </a>
</div>
</body>
</html>
