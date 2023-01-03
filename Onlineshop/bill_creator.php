<?php
require_once("./fpdf/fpdf.php");
if (isset($_GET['order_id'])) {
	$orderid = $_GET['order_id'];
	if (!is_numeric($_GET['order_id'])) {
		#kiểm tra SQLI kiểu số.
		include("index.php");
		echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
		exit();
	}
} else
	header("location: index.php");

// instantiate and use the dompdf class

$filename = "order" . $_GET['order_id'] . ".pdf";
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->AddPage();
$content = $pdf->Output('./bill/' . $filename, 'F');

require_once './dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();

$options->set('chroot', '../../htdocs');
$dompdf = new Dompdf();
$dompdf->setOptions($options);
ob_start();  // start output buffering
$orid = $_GET['order_id'];
include 'bill.php';
$content = ob_get_clean();
$dompdf->loadHtml($content);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();
$output = $dompdf->output();
file_put_contents('./bill/' . $filename, $output);

header("location: index.php");
