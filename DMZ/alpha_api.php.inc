#!/usr/bin/php
<?php

function getAPIdata($symbols, $latestTime)
{
if($latestTime == "FALSE")
{
	$latestTime = '2015-01-10 14:10:00';
}
$dataParsed = array();
error_reporting(E_ALL ^ E_NOTICE);
foreach($symbols as $mySymbol)
{
echo $mySymbol.PHP_EOL;
$alpha_key = 'N7B9EMN3F2CHEW75';
$now = date('Y-m-d H:i:00',time());
$tempTime = $now;
echo $tempTime.PHP_EOL;
$url = 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&interval=1min&symbol='.$mySymbol.'&outputsize=full&apikey='.$alpha_key;

$json_obj = file_get_contents($url);
if(!isset($json_obj) || isset($json_obj["Error Message"]))
{
	return "Error getting data from API";
}
$data = json_decode($json_obj, true);
$arrayLength = count($data['Time Series (1min)']);
if(date('N',strtoTime($tempTime)) == '6')
{	
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-1 day'));
}
elseif(date('N',strtoTime($tempTime)) == '7')
{	
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-2 day'));
}
elseif(date('Hi',strtoTime($tempTime)) > '1600')
{	
	$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime));
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
$i = 0;
while(($i < $arrayLength))
{
	if($tempTime == $latestTime)
	{
		break;
	}
	if(date('N',strtoTime($tempTime)) == '6')
	{	
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-1 day'));
	}
	elseif(date('N',strtoTime($tempTime)) == '7')
	{	
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-2 day'));
	}
	elseif(date('Hi',strtoTime($tempTime)) > '1600')
	{	
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime));
	}
	elseif(date('Hi',strtoTime($tempTime)) < '0930')
	{
		$tempTime = date('Y-m-d 16:00:00', strtotime($tempTime.'-1 day'));
	}
	$dataParsed['stocks'][$mySymbol][$tempTime] = $data['Time Series (1min)'][$tempTime]['1. open'];

	$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'-1 minute'));
	$i++;
}
}
return $dataParsed;

}

?>
