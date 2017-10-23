<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');
require_once('requestClient.php.inc');

$mytest = new stocksDB();
$username = "joe";
$mytest->checkUserStock($username);


class stocksDB
{
	private $logindb;
	private $myloggerClient;
	private $requestClient;
	private $listofsymbols = array('GOOG', 'AMZN');
	public function __construct()
	{
		$this->myLoggerClient = new loggerClient();
		$this->requestClient = new rabbitClient("testRabbitMQ.ini", 'DMZServer');
		$this->logindb = new mysqli("127.0.0.1","root","password","stocksdb");
		if ($this->logindb->connect_errno != 0)
		{
			echo "Sending error message to logger".PHP_EOL;
			$this->myLoggerClient->sendLog("database.log", 3,"Error connecting to database: ".$this->logindb->connect_error." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
		echo "correctly connected to database".PHP_EOL;
	}

	public function buyStock($symbol,$quantity)
	{
		try{
			$sym = $this->logindb->real_escape_string($symbol);
			$q1 = "select marketprice from stockInfo where symbol = '$sym';";
			$r1 = $this->logindb->query($q1);
			$price = $r1->fetch_assoc();
			$marketPrice = $price["marketPrice"];
			$totalPrice = $marketPrice * $quantity;
			$q2 = "select stockName from stockInfo where symbol = '$sym';";
			$r2 = $this->logindb->query($q2);
			$name = $r2->fetch_assoc();
			$stockName = $name["stockName"];
			$q3 = "insert into userStocks(symbol,stockName,quantity,purchasePrice,totalPrice) values('$sym','$stockName','$quantity','$marketPrice','$totalPrice')";
			$q4 = "update userInfo set balance = balance - '$totalPrice' where ID = '$id';";
			$r3 = $this->logindb->query($q3);
			$r4 = $this->logindb->query($q4);
			return "You have bought "+ $quantity +"stocks of "+ $stockName;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function sellStock($symbol,$quantity)
	{
		try{
			$sym = $this->logindb->real_escape_string($symbol);
			$q1 = "select quantity from userStocks where symbol = '$symbol'";
			$r1 = $this->logindb->query($s);
			$currentQuantity = $r1->fetch_assoc();
			if ($currentQuantity < $quantity)
			{
				return "Attempted to sell more stocks than currently owned. Nice try.";
			}
			$q2 = "select price from stocks where symbol = '$symbol';";
			$r2 = $this->logindb->query($q2);
			$price = $r2->fetch_assoc();
			$marketPrice = $price["marketPrice"];
			$totalPrice = $marketPrice * $quantity;
			$q3 = "select stockName from stocks where symbol = '$sym';";
			$r3 = $this->logindb->query($q3);
			$name = $r3->fetch_assoc();
			$stockName = $name["stockName"];
			$q4 = "update userInfo set balance = balance + $totalPrice where ID = $id;";
			if ($currentquantity == $quantity)
			{
				$q = "delete from userStocks where symbol = '$sym';";
				return "Successfully sold all stocks of "+ $stockName;
			}
			$q5 = "update userStocks set quantity-=$quantity,totalvalue-=$totalPrice where username ='$username'";
			$r4 = $this->logindb->query($q4);
			$r5 = $this->logindb->query($q5);
			return "Successfully sold "+ $quantity + "shares of "+ $stockName;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
		}
	

	public function checkUserStock($username)
	{
		try
		{
			$un = $this->logindb->real_escape_string($username);
			$response = $this->updateStocks();
			if($response != "Error getting data from API")
			{
				$statement = "select * from userStocks where username = '$username';";
				$response = $this->logindb->query($statement);
				$response = $response->fetch_assoc();

			}
			
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function updateStocks()
	{
		try
		{

			$statement = "select timestamp from stockInfo order by timestamp DESC Limit 1;";
			$response = $this->logindb->query($statement);
			$response = $response->fetch_assoc();
			$request['type'] = 'updateData';
			$request['symbols'] = $this->listofsymbols;	
			$request['latestTime'] = $response['timestamp'];
			echo "Pulling data from API".PHP_EOL;
			$response = $this->requestClient->make_request($request);
			if($response == "Latest data already in database")
			{
				return "Latest data already in database".PHP_EOL;
			} 
			elseif($response == "Error getting data from API")
			{
				echo "Error getting data from API".PHP_EOL;
				return "Error getting data from API".PHP_EOL;
			}		
			else
			{		
				var_dump($response);
				$statement = $this->logindb->prepare('insert into stockInfo (`symbol`, `timestamp`, `open`, `close`, `high`, `low`, `volume`) values(?, ?, ?, ?, ?, ?, ?);');
				$statement->bind_param("ssddddi",$symbol, $timestamp,$open,$close,$high,$low,$volume);
				foreach($response as $mySymbol => $data)
				{
					$symbol = $mySymbol;
					foreach($response[$mySymbol] as $time => $values)
					{
						$timestamp = $time;
					 	$open = $values['open'];
						$close = $values['close'];
						$high = $values['high'];
						$low = $values['low'];
						$volume = $values['volume']; 		
						$statement->execute();
					}
				}
			}
			return "Inserted new data into table";
			
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	
}
?>
