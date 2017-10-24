#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('stock.php.inc');
require_once('loggerClient.php.inc');

function buyStock($symbol,$quantity,$username)
{
	$dbConn = new loginDB();
	$response = $dbConn->buyStock($symbol,$quantity,$username);
	echo $response.PHP_EOL;
	return $response;
}
function sellStock($symbol,$quantity,$username)
{
	$dbConn = new loginDB();
	$response = $dbConn->sellStock($symbol,$quantity,$username);
	echo $response.PHP_EOL;
	return $response;
}
function checkUserStocks($username)
{
	$dbConn = new stocksDB();
	$response = $dbConn->checkUserStocks($username);
	echo $response.PHP_EOL;
	return $response;
}
function checkStocks()
{
	$dbConn = new stocksDB();
	$response = $dbConn->checkStocks();
	echo $response.PHP_EOL;
	return $response;
}
function searchStock()
{
	$dbConn = new stocksDB();
	$response = $dbConn->searchStock
	$echo $response.PHP_EOl;
	return $response;
}

function requestProcessor($request)
{
	try
	{
		echo "received request".PHP_EOL;
		var_dump($request);
		if(!isset($request['type']))
		{
			return "ERROR: unsupported message type";
		}
		switch ($request['type'])
		{
			case "buy":
				if(empty($request['quantity']) || empty($request['symbol']))
				{
					echo "Stock symbol or Quantity not given.".PHP_EOL;
					return "Stock symbol or Quantity not given.";
				}
				return buyStocks($request['symbol'],$request['quantity']);
			case "sell":
				if(empty($request['symbol']) || empty($request['quantity']))
				{
					echo "Stock symbol or Quantity not given.".PHP_EOL;
					return "Stock symbol or Quantity not given.";
				}
				return sellStocks($request['symbol'],$request['quantity']);
			case "getUserStocks":
				if(empty($request['username']))
				{
					echo "Username not given.".PHP_EOL;
					return "Username not given.";
				}
				return checkUserStocks($request['username']);
			case "search":
				if(empty$(request['stockName']))
				{
					echo "Stock Name not given".PHP_EOL;
					return"Stock Name not given.";
				}
		}
	
		return array("returnCode" => '0', 'message'=>"Server received request and processed");
	}

	catch(Exception $e)
	{
		$mylogger = new loggerClient();
		$mylogger->sendLog("stocksProc.log",2,"Error with getting stocks: ".$e." in ".__FILE__." on line ".__LINE__);
		exit(1);
	}
}

$server = new rabbitMQServer("testRabbitMQ.ini","stockServer");

$server->process_requests('requestProcessor');
exit();
?>

