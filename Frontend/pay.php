<?php

use PayPal\Api\Payment;

require 'paypal/start.php';

if (!isset($_GET['success'], $_GET['paymentId'], $_GET['PayerID']))
{
	//kill
}
if ((bool)$_GET['success'] === false
{
	//kill
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$payment = Payment::get($paymentId, $paypal);

$execute = new PaymentExecution();
$execute->setPayerId($payerId);

try
{
	$result = $payment->execute($execute, $paypal);
}
catch (Exception $e)
{
	$data = json_decode($e->getData());
	var_dump($data);
	//kill
}

echo 'Funds Added to Balance';
//redirect to main or whatever


?>
