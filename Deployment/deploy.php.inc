<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');

class deployDB
{
	private $logindb;
	private $myloggerClient;
	public function __construct()
	{
		$this->myLoggerClient = new loggerClient();
		$this->logindb = new mysqli("127.0.0.1","root","password","stocksdb");
		if ($this->logindb->connect_errno != 0)
		{
			echo "Sending error message to logger".PHP_EOL;
			$this->myLoggerClient->sendLog("database.log", 3,"Error connecting to database: ".$this->logindb->connect_error." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
		echo "correctly connected to database".PHP_EOL;
	}

	public function newBundle($path, $serverType, $ip)
	{
		try{
			$serverType = $this->logindb->real_escape_string($serverType);
			$statement = "select version from deployment where version = max and not deprecated;";
			$response = $this->logindb->query($statement);
			echo "checking version for $serverType".PHP_EOL;
			while ($row = $response->fetch_assoc();
			{
				if (password_verify($pw,$row["password"]))
				{
					echo "passwords match for $username".PHP_EOL;
					
					$statement = "select balance from userInfo where username = '$un';";
					$balanceresponse = $this->logindb->query($statement);
					$response = $balanceresponse->fetch_assoc();

					$response['balance'] = 	$response['balance'];
					$response['login'] = "LoginSuccess";
					return $response;// password match
				}
				echo "passwords did not match for $username".PHP_EOL;
			}
			return "LoginFailed";//no users matched username
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
}
?>
