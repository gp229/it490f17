<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');

class loginDB
{
	private $logindb;
	private $myloggerClient;
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

	public function validateLogin($username,$password)
	{
		try{
			$un = $this->logindb->real_escape_string($username);
			$pw = $this->logindb->real_escape_string($password);
			$statement = "select * from userlogin where username = '$un';";
			$response = $this->logindb->query($statement);
			while ($row = $response->fetch_assoc())
			{
				echo "checking password for $username".PHP_EOL;
				if ($row["password"] == $pw)
				{
					echo "passwords match for $username".PHP_EOL;
					return "LoginSuccess";// password match
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
	public function registerUser($username,$password)
	{
		try{
			$un = $this->logindb->real_escape_string($username);
			$pw = $this->logindb->real_escape_string($password);
			$statement = "select * from userlogin where username = '$un';";
			$response = $this->logindb->query($statement);
			$row = $response->fetch_assoc();
			if($row > 0)
			{
				return "User already in database.";
			}
			$statement = "select  into userlogin(username, password) Values('$un', '$pw');";
			$response = $this->logindb->query($statement);
			return "User Registered";
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
	public function getUserStocks($username)
	{
		try{
			$un = $this->logindb->real_escape_string($username);

			$statement = "select top 1 timestamp from stocks order by timestamp DESC";
			$response = $this->logindb->query($statement);
			$row = $response->fetch_assoc();
			if($row == 0)
			{
				//Pull all stock data
				//return "No stock data found.";
			}
			echo "Timestamp: ".$row;
			$date = time();
			$row = strtotime($row); 
			$statement = "select stocks from userStocks where username = '$un';";	
			$response = $this->logindb->query($statement);
			$row = $response->fetch_assoc();
			if($row == 0)
			{
				return "User not found in database.";
			}
			$statement = "insert into users(username, password) Values('$un', '$pw');";
			$response = $this->logindb->query($statement);
			return "User Registered";
		}
		catch(Error $e)
		{
			$this->myLoggerClient->sendLog("database.log", 4,"Fatal Error connecting to database: ".$e." in ".__FILE__." on line ".__LINE__);
			exit(1);
		}
	}
}
?>
