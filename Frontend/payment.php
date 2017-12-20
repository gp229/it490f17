<!DOCTYPE html>

<?php
include('header.php');
require_once('path.inc');
require_once('requestClient.php.inc');
require_once('loggerClient.php.inc');
?>

<html lang=en>
<head>
	<meta charset=utf-8>
	<title>test payment</title>
</head>
<body>

<div class="payment-container">
	<h2 class="header">Add to Balance</h2>
	<form action="checkout.php" method="post" autocomplete="off">
		<label for="amount">
		Amount
		<input type="text" name="funds">
		</label>
		
		<input type="submit" value="Pay">
	</form>



<!-- Forms for paypal buttons -->
<h2>Add $100 to Balance</h2>
<table>
<tr>
	<td> 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="EW5GT7UWLDHZA">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
	</td>
</tr>
</table>
<h2>Add $1000 to Balance</h2>
<table>
<tr>
	<td> 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="A9ECZSMBMFYVC">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

	</td>
</tr>
</table>
<h2>Add $10000 to Balance</h2>
<table>
<tr>
	<td>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="3K6ZXX7LLFMJL">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

	</td>
</tr>
</table>





</body>













</html>
