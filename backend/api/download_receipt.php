<?php
require_once(__DIR__ . '/../fpdf/fpdf.php');
include("../config/db.php");

// 🔍 Get ID
$id = $_GET['donation_id'] ?? 0;

if(!$id){
    die("Invalid ID");
}

// 🔍 Fetch donation
$sql = "SELECT * FROM donations WHERE id='$id'";
$result = $conn->query($sql);

if(!$result || $result->num_rows == 0){
    die("No donation found");
}

$data = $result->fetch_assoc();

// 📄 Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Donation Receipt',0,1,'C');

$pdf->Ln(10);

// Data
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Name: '.$data['name'],0,1);
$pdf->Cell(0,10,'Amount: '.$data['amount'],0,1);
$pdf->Cell(0,10,'Payment Method: '.$data['payment_method'],0,1);
$pdf->Cell(0,10,'Date: '.$data['created_at'],0,1);

$pdf->Ln(10);
$pdf->Cell(0,10,'Thank you!',0,1,'C');

// 🔥 IMPORTANT
$pdf->Output('D', 'receipt.pdf'); // force download
exit;
?>
