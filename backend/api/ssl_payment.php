<?php
require_once __DIR__ . '/../config/config.php';

$store_id    = getenv('SSL_STORE_ID');
$store_passwd = getenv('SSL_STORE_PASS');
$site_url    = rtrim(getenv('SITE_URL'), '/');
$ssl_mode    = getenv('SSL_MODE') ?: 'sandbox';

$amount      = $_GET['amount']      ?? 0;
$name        = $_GET['name']        ?? '';
$email       = $_GET['email']       ?? '';
$donation_id = $_GET['donation_id'] ?? 0;

$post_data = [];
$post_data['store_id']     = $store_id;
$post_data['store_passwd'] = $store_passwd;
$post_data['total_amount'] = $amount;
$post_data['currency']     = "BDT";
$post_data['tran_id']      = $donation_id;

$post_data['success_url'] = $site_url . "/backend/api/success.php";
$post_data['fail_url']    = $site_url . "/backend/api/fail.php";
$post_data['cancel_url']  = $site_url . "/backend/api/cancel.php";

$post_data['cus_name']  = $name;
$post_data['cus_email'] = $email;

$gateway_url = ($ssl_mode === 'live')
    ? "https://securepay.sslcommerz.com/gwprocess/v4/api.php"
    : "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $gateway_url);
curl_setopt($handle, CURLOPT_POST, true);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

$content = curl_exec($handle);
$result  = json_decode($content, true);

if ($result && isset($result['GatewayPageURL'])) {
    header("Location: " . $result['GatewayPageURL']);
} else {
    echo "Payment gateway error. Please try again.";
}
?>
