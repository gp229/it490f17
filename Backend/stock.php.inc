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
	private $listofsymbols = array('GOOG', 'AMZN');// 'AAPL', 'INTC', 'MSFT', 'CERN', 'AET', 'AXTA','NFLX');
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
	public function listStocks()
	{
		$response = $this->updateStocks();
		if($response != "Error getting data from API")
		{
			$query = "select * from stockInfo where timestamp = (select max(timestamp) from stockInfo);";
			$response = $this->logindb->query($query);
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
	
	private function triple_exponential_smoothing($series, $slen, $alpha, $beta, $gamma, $num_predictions)
	{
		$tempTime = end($series);
		$tempTime = $tempTime['timestamp'];
		$series = array_column($series, "close");
		$num_seasons = count($series)/$slen;
		for($j=0;$j < $num_seasons;$j++)
		{
			$season_averages[$j] = array_sum(array_slice($series, $slen*$j,$slen))/$slen;
		}
		for($i=0;$i < $slen;$i++)
		{
			$sum_of_vals_over_avg = 0.0;
			for($j=0;$j < $num_seasons;$j++)
			{
				$sum_of_vals_over_avg += $series[$slen*$j+$i]-$season_averages[$j];
			}
			$seasonals[$i] = $sum_of_vals_over_avg/$num_seasons;
		}
		$sum = 0;
		for($i=0;$i < $slen;$i++)
		{
			$sum += ($series[$i+$slen] - $series[$i]) / $slen;
		}    
		$trend = $sum / $slen;
		$smooth = $series[0];
		$result = array();
		for($i=1;$i < count($series) + $num_predictions;$i++)
		{
			if($i >= count($series))
			{
				$m = $i - count($series) + 1;
				$tempTime = date('Y-m-d H:i:00', strtotime($tempTime.'+1 minute'));
				if(date('N',strtoTime($tempTime)) == '6')
				{
					$tempTime = date('Y-m-d 09:30:00', strtotime($tempTime.'+2 day'));
				}
				elseif(date('N',strtoTime($tempTime)) == '7')
				{
					$tempTime = date('Y-m-d 09:30:00', strtotime($tempTime.'+1 day'));
				}
				if(date('Hi',strtoTime($tempTime)) > '1600')
				{
					$tempTime = date('Y-m-d 09:30:00', strtotime($tempTime.'+1 day'));
				}
				$tempArray['timestamp'] = $tempTime;
				$tempArray['close'] = ($smooth + $m*$trend) + $seasonals[$i%$slen];
				array_push($result, $tempArray);
			}
			else
			{
				$val = $series[$i];
				$last_smooth = $smooth;
				$smooth = $alpha*($val-$seasonals[$i%$slen]) + (1-$alpha)*($smooth+$trend);
				$trend = $beta * ($smooth-$last_smooth) + (1-$beta)*$trend;
				$seasonals[$i%$slen] = $gamma*($val-$smooth) + (1-$gamma)*$seasonals[$i%$slen];
			}
		}
		return $result;
	}
	public function searchStock($stockname)
	{
		$stock = $this->logindb->real_escape_string($stockname);
		$response = $this->updateStocks();
		if($response != "Error getting data from API")
		{
			$statement = "select timestamp, close from stockInfo where symbol = '$stock';";
			$response = $this->logindb->query($statement);	
			$myArray = array();
			$i = 0;
			while ($row = $response->fetch_assoc())
			{
				$myArray[$i] = $row;
				$i++;
			}
			$response = $myArray;
			if(count($myArray) > 100)
			{
				$slen = 7;
				unset($response);
				$response[$stock] = $myArray;
				$response[$stock." Forecast"] = $this->triple_exponential_smoothing($myArray, $slen, 0.996970912, 0.00133329, .9, 500);
			}
		}
		return $response;
		
	}
	
	
	public function myStockStats($username)
	{
		try
		{
			$un = $this->logindb->real_escape_string($username);
			$statement = "SELECT symbol, min(timestamp), sum(quantity) FROM userStocks s1 where username = '$un' group by symbol;";
			$response = $this->logindb->query($statement);	
			$myArray = array();
			while ($row = $response->fetch_assoc())
                        {
				$symbol = $row['symbol'];
				$myArray[$symbol] = $row;
			}
			$response = $myArray;	
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
				$statement = "SELECT timestamp, symbol FROM userStocks s1 WHERE ID in (select MIN(ID) from userStocks where username = '$un' group by symbol);";
				$response = $this->logindb->query($statement);	
				$myArray = array();
				while ($row = $response->fetch_assoc())
                        	{
					$timestamp = $row['timestamp'];
					$symbol = $row['symbol'];
					$statement = "select timestamp, open, close, high, low, volume from stockInfo where timestamp >= '$timestamp' and symbol = '$symbol';";
	                                $stocklist = $this->logindb->query($statement);
					while ($stock = $stocklist->fetch_assoc())
                        		{
						$timestamp = $stock['timestamp'];
						unset($stock['timestamp']);
						$myArray[$symbol][$timestamp] = $stock;
					}
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
	
	public function transUpdateHistory($username,$itemNumber,$transactionId,$paymentAmount,$paymentStat)
	{
		try{
			$un = $this->logindb->real_escape_string($username);
			$itemNum = $this->logindb->real_escape_string($itemNumber);
			$transId = $this->logindb->real_escape_string($transactionId);
			$paymentAmt = $this->logindb->real_escape_string($paymentAmount);
			$payStat = $this->logindb->real_escape_string($paymentStat);
			$query = "insert into paymentHistory(username,itemNumber,transactionId,paymentAmount,paymentStatus) values('$un','$itemNum','$transId','$paymentAmt','$payStat');";
			$req = $this->logindb->query($query);
			$response = "Transaction history updated.";
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function updateBalance($paymentAmount,$username)
	{
		try{
			$paymentAmt = $this->logindb->real_escape_string($paymentAmount);
			$un = $this->logindb->real_escape_string($uesername);
			$query = "update userInfo set balance = balance + '$paymentAmt' where username = '$un';";
			$response = "Added to balance";
			return $response;
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e" in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
}
?>
