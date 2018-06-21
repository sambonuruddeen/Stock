<?php 

require_once 'core.php';

if($_POST) {

	$startDate = $_POST['startDate'];
	$date = DateTime::createFromFormat('m/d/Y',$startDate);
	$start_date = $date->format("Y-m-d");


	$endDate = $_POST['endDate'];
	$format = DateTime::createFromFormat('m/d/Y',$endDate);
	$end_date = $format->format("Y-m-d");

	$sql = "SELECT * FROM orders WHERE order_date >= '$start_date' AND order_date <= '$end_date' and order_status = 1";
	$query = $connect->query($sql);

	if($query->fetch_assoc() > 0) {

	$table = '
	<div style="text-align:center; font-family:Algerian; font-size: 2.5em; font-weight:bold;">
	TARKUNYA VENTURES LIMITED</div>
		';

	

	$table .= '

		<table border="1" cellspacing="0" cellpadding="1" style="width:90%; border:1px solid #000; margin: 0 auto;">
		<tr colspan="7">
			<div style="text-align:center; ">SALES REPORT FROM: '.$startDate.' To: '.$endDate.'</div>
		</tr>
		<tr>
			<th>S/n</th>
			<th>Order Date</th>
			<th>Client Name</th>
			<th>Paid Amount</th>
			<th>Due Amount</th>
			<th>Discount</th>
			<th>Grand Total</th>
		</tr>

		<tr>';
		$sn=1;
		$totalAmount = "";
		while ($result = $query->fetch_assoc()) {
			
			$OID = $result['order_id'];
			$prodSQL = "SELECT product_name FROM product INNER JOIN order_item ON product.product_id=order_item.product_id WHERE order_item.order_id= $OID";
			$prodQuery = $connect->query($prodSQL);
			$prodResult = $prodQuery->fetch_assoc();

			$table .= '<tr>
				<td><center>'.$sn++.'</center></td>
				<td><center>'.$result['order_date'].'</center></td>
				<td><center>'.ucwords($result['client_name']).'</center></td>
				<td><center>'.$result['paid'].'</center></td>
				<td><center>'.$result['due'].'</center></td>
				<td><center>'.$result['discount'].'</center></td>
				<td><center>'.$result['grand_total'].'</center></td>
			</tr>';	
			$totalAmount =0;
			$totalAmount += $result['grand_total'];
		}
		$table .= '
		</tr>

		<tr style="font-weight:bolder;">
			<td colspan="6" style="text-align: right; padding-right: 20px;">Total Amount</td>
			<td style="text-align: left;"><center>'.number_format($totalAmount,2).'</center></td>
		</tr>
	</table>
	';	

	echo $table;
}
else {
	echo "<div style='text-align:center;'>No sales recorded for the selected period! Change the Date and try again.</div>";
}
}

?>