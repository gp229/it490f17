<!DOCTYPE html>

<?php
include('header.php');
require_once('path.inc');
require_once('requestClient.php.inc');
require_once('loggerClient.php.inc');
session_start();
try{
	$request['type'] = "addBal";
	$myClient = new rabbitClient("testRabbitMQ.ini","stockServer");
	$response = $myClient->make_request($request);
}
catch(Error $e)
{
	$mylogger = new loggerClient();
	$mylogger->sendLog("userauth.log",2,"Error with user authentication: ".$e." in ".__FILE__." on line ".__LINE__);
	$response = "Sorry, something went wrong.";
}
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
?>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>Payment success page</title>
</head>

<body>


<script>
<?php echo "var username = '" .$_SESSION['loginUser']. "';"; ?>


var itemNumber = "<?php echo $itemNumber ?>";
var transactionId = "<?php echo $transactionId ?>";
var paymentAmount = <?php echo $paymentAmount ?>;
var currency = "<?php echo $currency ?>";
var paymentStat = "<?php echo $paymentStat ?>";
submitAddBal(username,itemNumber,transactionId,paymentAmount,currency,paymentStat);


function submitAddBal(username,itemNumber,transactionId,paymentAmount,currency,paymentStat)
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
request.send("type=addBal&username="+username+"&itemNum="+itemNumber+"&transactionId="+transactionId+"&paymentAmount="+paymentAmount+"&currency="+currency+"&paymentStat="+paymentStat);
}
</script>
</body>
</html>
