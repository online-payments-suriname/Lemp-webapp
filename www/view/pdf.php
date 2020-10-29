<?php
require('controller/autoloader.php');
$pdf = new \model\pdf('L');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->Ln(10);
$pdf->WriteHTML($_SESSION['table']);

$pdf->Output($pdf->title,'I');
?>
