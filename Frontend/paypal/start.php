<?php

require 'PayPal-PHP-SDK/autoload.php';

define('SITE_URL',//enter url here for main page)

$paypal = new \PayPal\Rest\ApiContext(new \PayPal\Auth\0AuthTokenCredential(
'AbhjD9PJc-CcJ0MXcI7K4R7pM8VrPKFwzj0EQzkxoPRRDv8L41xgGtvdeIUx6K0MZHfXExr2AkBid_VS',
'EIGjgT4t63jeJQVZ1xCs9ylcHp8VBgGHqZ1nh1lRIarPhBuRdCSQ5NGjffHT5ju4T-9QOw7ytRlS_VCB'));

?>
