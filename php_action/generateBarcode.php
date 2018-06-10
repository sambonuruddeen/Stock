<?php

require_once 'core.php';

$productId = $_POST['productId'];

$sql = "SELECT product_name, barcode FROM product WHERE product_id = $productId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

echo $row['product_name'].": ".$row['barcode'];

include 'src/BarcodeGenerator.php';
include 'src/BarcodeGeneratorPNG.php';
include 'src/BarcodeGeneratorSVG.php';
include 'src/BarcodeGeneratorHTML.php';
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();


$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode($row['barcode'], $generator::TYPE_EAN_13);
//$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
//echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($row['barcode'], $generator::TYPE_EAN_13)) . '">';

