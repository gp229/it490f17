<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');
require_once('requestClient.php.inc');

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

	public function buyStock($symbol,$quantity,$username)
	{
		try{
			$response = $this->updateStocks();
			if($response != "Error getting data from API")
			{
			$sym = $this->logindb->real_escape_string($symbol);
			$un = $this->logindb->real_escape_string($username);
			$q1 = "select close, timestamp from stockInfo where symbol = '$sym' and timestamp = (select max(timestamp) from stockInfo where symbol = '$sym');";
			$r1 = $this->logindb->query($q1);
			$price = $r1->fetch_assoc();
			$marketPrice = $price['close'];
			$timestamp = $price['timestamp'];
			$totalPrice = $marketPrice * $quantity;
			$q1 = "select balance from userInfo where username = '$un';";
			$r1 = $this->logindb->query($q1);
			$price = $r1->fetch_assoc();
			$balance = $price['balance'];
			if($balance < $totalPrice)
			{
				echo "Not enough money to purchase stocks.";
				return "Not enough money to purchase stocks.";
			}
			$q3 = "insert into userStocks(symbol,timestamp, username, quantity,purchasePrice,totalValue) values('$sym','$timestamp','$un','$quantity','$marketPrice','$totalPrice');";
			$q4 = "update userInfo set balance = balance - '$totalPrice' where username = '$un';";
			$r3 = $this->logindb->query($q3);
			$r4 = $this->logindb->query($q4);
			$response = "You have bought ".$quantity." stocks of ".$symbol;
			}
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function sellStock($symbol,$quantity,$username)
	{
		try{
			$response = $this->updateStocks();
			if($response != "Error getting data from API")
			{
			$un = $this->logindb->real_escape_string($username);
			$sym = $this->logindb->real_escape_string($symbol);
			$q1 = "select sum(quantity) from userStocks where symbol = '$symbol' and username = '$un';";
			$r1 = $this->logindb->query($q1);
			$currentQuantity = $r1->fetch_assoc();
			$currentQuantity= $currentQuantity['sum(quantity)'];
			if ($currentQuantity < $quantity)
			{
				return "Attempted to sell more stocks than currently owned. Nice try.";
			}
			$q2 = "select close from stockInfo where symbol = '$sym' and timestamp = (select max(timestamp) from stockInfo where symbol = '$sym');";
			$r2 = $this->logindb->query($q2);
			$price = $r2->fetch_assoc();
			$marketPrice = $price['close'];
			$totalPrice = $marketPrice * $quantity;
			$q4 = "update userInfo set balance = balance + '$totalPrice' where username = '$un';";
			$r4 = $this->logindb->query($q4);
			if ($currentQuantity == $quantity)
			{
				$q4= "delete from userStocks where symbol = '$sym' and username = '$un';";
				$r4 = $this->logindb->query($q4);
				echo "Successfully sold all stocks of ".$symbol;
				echo "Successfully sold all stocks of ".$symbol;
			}
			else
			{
				$myQuantity = $quantity;
				while($quantity > 0)
				{
					$q5 = "select ID, quantity from userStocks where ID = (select Max(ID) from userStocks where symbol = '$sym' and username = '$username');";
					$r5 = $this->logindb->query($q5);
					$values = $r5->fetch_assoc();
					$id = $values['ID'];
					$currentQuantity = $values['quantity'];
					if($quantity >= $currentQuantity)
					{
						$q5= "delete from userStocks where id = '$id';";
					}
					else
					{
						$q5 = "update userStocks set quantity = quantity - '$quantity' where id ='$id';";
					}
					$r5 = $this->logindb->query($q5);
					$quantity -= $currentQuantity;	
					echo $quantity;			
				}
			}
			$response = "Successfully sold ".$myQuantity." shares of ".$sym;
			}
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function graphData($symbol,$type)
	{
		try
		{
			$sym = $this->logindb->real_escape_string($symbol);
			$typ = $this->logindb->real_escape_string($symbol);
			$query = "select '$typ' from stockInfo where symbol = '$sym' order by timeStamp DESC;";
			$response = $this->logindb->query($query);
			$result = $response->fetchAll();
			return var_dump($result);
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}

	}

	public function searchStock($stockName)
	{
		try
		{
			$row = array();
			$sn = $this->logindb->real_escape_string($stockName);
			$q1 = "select * from stockInfo where stockName = '$sn';";
			$r1 = $this->logindb->query($q1);
			while($row = $r1->fetch_assoc())
			{
				echo $row['stockName'];
			}
			if(!$r1)
			{
				return 0;
			}
			$result = $r1->fetch_assoc();
			if(isset($result['stockName']))
			{
				return $result;
			}
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function checkStocks()
	{
		try
		{
			$response = $this->updateStocks();
			if($response != "Error getting data from API")
			{
				$statement = "select * from stockInfo where timestamp = (select max(timestamp) from stockInfo);";
				$response = $this->logindb->query($statement);
				
				$i = 0;
				while ($row = $response->fetch_assoc())
				{
					$myArray[$i] = $row;
					$i++;
				}
				$response = $myArray;
			}
			
			return $response;
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
				$statement = "select * from userStocks where username = '$un';";
				$response = $this->logindb->query($statement);	

			}
			
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}


	private function updateStocks()
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
