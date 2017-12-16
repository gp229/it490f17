<? php

require 'paypal/start.php';
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;


if (!isset($POST['funds']))
{
	//die(); or whatever
}

$funds = $_POST['funds'];
$shipping = 0.0;

$total = $funds + $shipping;

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$item = new Item();
$item->setName('Funds')
	->setCurrency('USD')
	->setQuantity(1)
	->setPrice($funds);

$itemList = new ItemList();
$itemList->setItems([$item]);

$details = new Details();
details->setShipping($shipping)
	->setSubtotal($funds);

$amount = new Amount();
$amount->setCurrency('USD')
	->setTotal($total)
	->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($funds)
	->setItemList($itemList)
	->setDescription('Add funds to balance')
	->setInvoiceNumber(uniqid());

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(SITE_URL . '/pay.php?success=true')
	->setCancelUrl(SITE_URL . '/pay.php?success=false');

$payment = new Payment();
$payment->setIntent('sale')
	->setPayer($payer)
	->setRedirectUrls($redirectUrls)
	->setTransactions([$transaction]);

try
{
	$payment->create($paypal);
}
catch(Exception $e)
{
//logging kill
}

$approvalUrl = $payment->getApprovalLink();

header("Location: {$approvalUrl}");

?>
