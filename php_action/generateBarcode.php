<?php

require_once 'core.php';

$productId = $_POST['productId'];

//Test if barcode exists

$bcSQL = "SELECT barcode FROM product WHERE product_id=$productId";
$bcQR = $connect->query($bcSQL);
$bcRES = $bcQR->fetch_assoc();

if($bcRES['barcode'] != "") {

$sql = "SELECT product_name, barcode FROM product WHERE product_id = $productId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

echo $row['product_name'].": ".$row['barcode'];

} //Barcode Exist

//Barcode Doesn't exist
elseif($bcRES['barcode'] == "") {
$number_of_digits = 12;
$code = substr(number_format(time() * mt_rand(),0,'',''),0,$number_of_digits);

$updateSQL = "UPDATE product SET barcode=$code WHERE product_id=$productId";
$updateQUERY = $connect->query($updateSQL);
if($updateQUERY) {
$sql = "SELECT product_name, barcode FROM product WHERE product_id = $productId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

echo $row['product_name'].": ".$row['barcode'];
}
} //Barcode Doesn't exist

include 'src/BarcodeGenerator.php';
include 'src/BarcodeGeneratorPNG.php';
include 'src/BarcodeGeneratorSVG.php';
include 'src/BarcodeGeneratorHTML.php';
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();


$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo '<div id="bcd">';
echo ucfirst($row['product_name'].": ".$row['barcode'])."<br />";
echo $generator->getBarcode($row['barcode'], $generator::TYPE_EAN_13);
echo '</div>';


//$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
//echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($row['barcode'], $generator::TYPE_EAN_13)) . '">';

