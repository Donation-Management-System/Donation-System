<?php

$store_id = "mjind69d4027661e27";
$store_passwd = "mjind69d4027661e27@ssl";

$amount      = $_GET['amount']      ?? 0;
$name        = $_GET['name']        ?? '';
$email       = $_GET['email']       ?? '';
$donation_id = $_GET['donation_id'] ?? 0;

$post_data = array();
$post_data['store_id']     = $store_id;
$post_data['store_passwd'] = $store_passwd;

$post_data['total_amount'] = $amount;
$post_data['currency']     = "BDT";
$post_data['tran_id']      = $donation_id;

// ✅ FIXED: All 3 URLs were localhost — now use live server
$post_data['success_url'] = "https://donation-system.great-site.net/backend/api/success.php";
$post_data['fail_url']    = "https://donation-system.great-site.net/backend/api/fail.php";
$post_data['cancel_url']  = "https://donation-system.great-site.net/backend/api/cancel.php";

$post_data['cus_name']  = $name;
$post_data['cus_email'] = $email;

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, "https://sandbox.sslcommerz.com/gwprocess/v4/api.php");
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // InfinityFree SSL fix

$content = curl_exec($handle);
$result  = json_decode($content, true);

if($result && isset($result['GatewayPageURL'])){
    header("Location: " . $result['GatewayPageURL']);
} else {
    echo "Payment gateway error. Please try again.";
}
?>