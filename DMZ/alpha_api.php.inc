#!/usr/bin/php
<?php

function getAPIdata($symbols, $latestTime)
{
$dataParsed = array();
$alpha_key = 'N7B9EMN3F2CHEW75';
$now = date('Y-m-d H:i:00',time());
error_reporting(E_ALL ^ E_NOTICE);
foreach($symbols as $mySymbol)
{
echo $mySymbol.PHP_EOL;
$tempTime = $now;
echo "Latest time in database: "$latestTime.PHP_EOL;

$i = 0;
if($latestTime == "FALSE")
{
$i = 102;
}
while($i < 101){
	if($latestTime == $tempTime)
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
	$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'-1 minute'));
	$i++;
}
if($i > 100)
{
	echo "Pulling full api data";
	$url = 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&interval=1min&symbol='.$mySymbol.'&outputsize=full&apikey='.$alpha_key;
}
else
{
	echo "Pulling compact api data";
$url = 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&interval=1min&symbol='.$mySymbol.'&outputsize=compact&apikey='.$alpha_key;
}

$json_obj = file_get_contents($url);
if(empty($json_obj))
{
	return "Error getting data from API";
}
$data = json_decode($json_obj, true);
$arrayLength = count($data['Time Series (1min)']);
$tempTime = $now;
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

for($i = 0; $i <= 5; $i++)
{
if(!isset($data['Time Series (1min)'][$tempTime]))
{
	if($i == 5)
	{
		echo "Error getting data from API when checking minutes";
		return "Error getting data from API";
	}
	
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
	$dataParsed['stocks'][$mySymbol][$tempTime]['open'] = $data['Time Series (1min)'][$tempTime]['1. open'];
	$dataParsed['stocks'][$mySymbol][$tempTime]['high'] = $data['Time Series (1min)'][$tempTime]['2. high'];
	$dataParsed['stocks'][$mySymbol][$tempTime]['low'] = $data['Time Series (1min)'][$tempTime]['3. low'];
	$dataParsed['stocks'][$mySymbol][$tempTime]['close'] = $data['Time Series (1min)'][$tempTime]['4. close'];
	$dataParsed['stocks'][$mySymbol][$tempTime]['volume'] = $data['Time Series (1min)'][$tempTime]['5. volume'];

	$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'-1 minute'));
	$i++;
}
}
return $dataParsed;

}

?>
