<?php
require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';



//use Mike42\Escpos\PrintConnectors\FilePrintConnector;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

//try {

// Enter the share name for your USB printer here
    $connector = new WindowsPrintConnector("Xprinter_XP-365B");
    $printer = new Printer($connector);

include 'php_action/core.php';

$orderId = 38;//$_POST['orderId'];

$sql = "SELECT order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due FROM orders WHERE order_id = $orderId";

$orderResult = $connect->query($sql);
$orderData = $orderResult->fetch_array();

$orderDate = $orderData[0];
$clientName = $orderData[1];
$clientContact = $orderData[2]; 
$subTotal = $orderData[3];
$vat = $orderData[4];
$totalAmount = $orderData[5]; 
$discount = $orderData[6];
$grandTotal = $orderData[7];
$paid = $orderData[8];
$due = $orderData[9];


$orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total,
product.product_name FROM order_item
    INNER JOIN product ON order_item.product_id = product.product_id 
 WHERE order_item.order_id = $orderId";
$orderItemResult = $connect->query($orderItemSql);

/* Information for the receipt */
while($row = $orderItemResult->fetch_array()) {     

$items =  array(new item($row[4], $row[1]));

}

$subtotal = new item('Subtotal', $subTotal);
$tax = new item('V.A.T', $vat);
$total = new item('Total', $grandTotal, true);
/* Date is kept the same for testing */
 $date = date('l jS \of F Y h:i:s A');
//$date = "Monday 6th of April 2015 02:56:25 PM";

/* Start the printer */
//$logo = EscposImage::load("resources/escpos-php.png", false);
//$printer = new Printer($connector);


/* Name of shop */
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("Tarkunya Ventures.\n");
$printer -> selectPrintMode();
$printer -> text("Wunti Street.\n");
$printer -> feed();

/* Title of receipt */
$printer -> setEmphasis(true);
$printer -> text("SALES INVOICE\n");
$printer -> setEmphasis(false);

/* Items */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item('', 'N'));
$printer -> setEmphasis(false);
foreach ($items as $item) {
    $printer -> text($item);
}
$printer -> setEmphasis(true);
$printer -> text($subtotal);
$printer -> setEmphasis(false);
$printer -> feed();

/* Tax and total */
$printer -> text($tax);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($total);
$printer -> selectPrintMode();

/* Footer */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Thank you for shopping at Tarkunya Ventures\n");
$printer -> text("For Enquiries , please call 08022422356\n");
$printer -> feed(2);
$printer -> text($date . "\n");
$printer -> feed(10);

/* Cut the receipt and open the cash drawer */
$printer -> cut();


$printer -> close();

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = 'N';
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
/*} catch(Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}*/