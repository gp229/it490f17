<!DOCTYPE html>

<?php
include('header.php');
require_once('path.inc');
require_once('requestClient.php.inc');
require_once('loggerClient.php.inc');

?>

<?php
//Get transaction details from URL
//item number 1 is $100 dollars, 2 is $1000, 3 is $10000
$itemNumber = $_GET['item_number'];
$transactionId = $_GET['tx'];
$paymentAmount = $_GET['amt'];
$currency = $_GET['cc'];
$paymentStat = $_GET['st'];

if ($paymentStat=="Completed")
{
echo "Payment successfully received";
}
$itemNumber = 1;
$transactionId = "12ab";
$paymentAmount = 31.0;
$currency = 'USD';
$paymentStat = 'Completed';
?>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>Payment success page</title>
</head>

<body>

<h1> Payment successfully received</h1>

<script>
submitAddBal('lmao1',<?php$itemNumber?>,<?php$transactionId?>,<?php$paymentAmount?>,<?php$currency?>,<?php$paymentStat?>);


function submitAddBal(username, itemNumber,transactionId,paymentAmount,currency,paymentStat)
{
var request = new XMLHttpRequest();
request.open("POST","stock.php",true);
request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
request.onreadystatechange=function ()
{
	if((this.readyState == 4)&&(this.status == 200))
	{
	HandleResponse(this.reponseText);
	}

}
request.send("type=addBal&username="+user+"paymentAmount="+paymentAmount+"&itemNum="+itemNumber+"&transactionId="+transactionId+"&currency="+currency+"&paymentStat="+paymentStat);
}
</script>
</body>
</html>
