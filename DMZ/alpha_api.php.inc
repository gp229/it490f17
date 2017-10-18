#!/usr/bin/php
<?php

$request = array('JPM', 'GOOG');
$stuff = getAPIdata($request, '2017-10-18 14:10:30');
var_dump($stuff);
function getAPIdata($symbols, $latestTime)
{
foreach($symbols as $mySymbol)
{
$alpha_key = 'N7B9EMN3F2CHEW75';
$now = date('Y-m-d H:i:00',time());
$tempTime = $now;
$url = 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&interval=1min&symbol='.$mySymbol.'&outputsize=full&apikey='.$alpha_key;

$json_obj = file_get_contents($url);
if($json_obj){
$data = json_decode($json_obj, true);
$arrayLength = count($data['Time Series (1min)']);
if(date('Hi',strtoTime($tempTime)) > '1600')
{	
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime));
	echo $tempTime;
}
elseif(date('Hi',strtoTime($tempTime)) < '0930')
{
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-1 day'));
}

for($i = 0; $i < 5; $i++)
{
if(!isset($data['Time Series (1min)'][$tempTime]))
{
	echo "Latest minute not found, going back 1".PHP_EOL;
	
	$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'-1 minute'));
}
else
{
	break;
}
}
$dataParsed = array();

while($tempTime != $latestTime)
{
	if(date('Hi',strtoTime($tempTime)) > '1600')
	{	
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime));
		echo $tempTime;
	}
	elseif(date('Hi',strtoTime($tempTime)) < '0930')
	{
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-1 day'));
	}
	if($i > 1000) 
	{
		break;
	}
	$dataParsed['stocks'][$mySymbol][$tempTime] = $data['Time Series (1min)'][$tempTime]['1. open'];

	$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'-1 minute'));
	$i++;
}

}
}
return $dataParsed;
}


?>
