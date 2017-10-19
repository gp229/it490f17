#!/usr/bin/php
<?php

$symbols = array('GOOG', 'AMZN');
foreach($symbols as $mySymbol)
{

$now = date('Y-m-d H:i:00',time());
$tempTime = $now;
echo $tempTime.PHP_EOL;
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime));

$request['stocks'][$mySymbol]['time'] = $tempTime;
}

var_dump($request);
?>
