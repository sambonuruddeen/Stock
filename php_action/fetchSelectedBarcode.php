<?php 	

require_once 'core.php';

$barcode = $_POST['barcode'];

$sql = "SELECT * FROM product WHERE barcode = $barcode AND active = 1 AND status = 1 AND quantity > 0 ";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows 

$connect->close();

echo json_encode($row);