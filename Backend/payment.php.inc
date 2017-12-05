<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');
require_once('requestClient.php.inc');

class stocksDB
{
	private $logindb;
	private $myloggerclient;
	private $requestClient;
	
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
