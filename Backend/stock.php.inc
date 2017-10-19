<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');

$dbConn	= new stocksDB();

class stocksDB
{
	private $logindb;
	private $myloggerClient;
	private $stockClient;
	public function __construct()
	{
		$this->myLoggerClient = new loggerClient();
		$this->logindb = new mysqli("127.0.0.1","root","password","login");
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
			$symbol = $this->logindb->real_escape_string($symbol);
			$s1 = "select price from stocks where symbol = '$symbol';";
			$marketPrice = $this->logindb->query($s1);
			$totalPrice = $marketPrice * $quantity;
			$s2 = "select stockName from stocks where symbol = '$symbol';";
			$stockName = $this->logindb->query($s2);
			$statement1 = "insert into userStocks(symbol,stockName,quantity,purchasePrice,totalPrice) values('$symbol','$stockName','$quantity','$marketPrice','$totalPrice')";
			$statement2 = "update userInfo set balance -= $totalPrice where ID = $id;";
			$response = $this->logindb->query($statement1);
			$response = $this->logindb->query($statement2);
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
			$s = "select quantity from userStocks where symbol = '$symbol'";
			$currentquantity = $this->logindb->query($s);
			if ($currentquantity < $quantity)
			{
				return "Attempted to sell more stocks than currently owned. Nice try.";
			}
			$symbol = $this->logindb->real_escape_string($symbol);
			$s1 = "select price from stocks where symbol = '$symbol';";
			$marketPrice = $this->logindb->query($s1);
			$totalPrice = $marketPrice * $quantity;
			$s2 = "select stockName from stocks where symbol = '$symbol';";
			$stockName = $this->logindb->query($s2);
			$statement2 = "update userInfo set balance += $totalPrice where ID = $id;";
			if ($currentquantity == $quantity)
			{
				$d1 = "delete from userStocks where symbol = '$symbol';";
				return "Successfully sold all stocks of "+ $stockName;
			}
			$statement1 = "update userStocks set quantity-=$quantity,totalvalue-=$totalPrice where username ='$username'";
			$response = $this->logindb->query($statement1);
			$response = $this->logindb->query($statement2);
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
			
			$statement1 = "select * from userStocks where username = '$username';";
			$response = $this->logindb->query($statement1);
			$response = "aa";
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
