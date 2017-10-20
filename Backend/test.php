<?php

$now = date('Y-m-d H:i:00', time());
$latestTime = date('Y-m-d 15:i:00', time());
echo $now.PHP_EOL;
echo $latestTime.PHP_EOL;
$i = 0;
$tempTime = $now;
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
	echo $tempTime.PHP_EOL;
	echo $i.PHP_EOL;
}
if($i > 100)
{
echo $i;
echo "SOmething";
}
else
{
	echo "less";
echo $i;
}

?>
