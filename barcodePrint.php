<?php
require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';



//use Mike42\Escpos\PrintConnectors\FilePrintConnector;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


try {

	include 'php_action/core.php';

$productId = $_GET['productId'];

//Test if barcode exists

$bcSQL = "SELECT product_name, barcode FROM product WHERE product_id=$productId";
$bcQR = $connect->query($bcSQL);
$bcRES = $bcQR->fetch_assoc();

	// Enter the share name for your USB printer here
	$connector = new WindowsPrintConnector("Xprinter_XP-365B");
	$printer = new Printer($connector);

	//* Barcodes */
//$barcodes = Printer::BARCODE_UPCA;

$printer -> setBarcodeHeight(80);

	$printer -> feed(1);
	$printer -> text(ucfirst($bcRES['product_name']).": ".$bcRES['barcode'] . "\n");
	$printer -> setJustification(Printer::JUSTIFY_CENTER);
	$printer -> barcode($bcRES['barcode']);
	$printer -> feed(10);

$printer -> cut();
$printer -> close();

} catch(Exception $e) {
	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}