<?php
require_once("loggerClient.php.inc");

class loginDB
{
private $logindb;
private $myloggerClient;
public function __construct()
{
	$this->myLoggerClient = new loggerClient();
	$this->logindb = new mysqli("127.0.0.1","root2","password","login");
	if ($this->logindb->connect_errno != 0)
	{
		echo "Sending error message to logger".PHP_EOL;
		$this->myLoggerClient->sendLog("database.log", 2,"Error connecting to database: ".$this->logindb->connect_error." in ".__FILE__." on line ".__LINE__);
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$statement = "select * from users where screenname = '$un';";
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
public function registerUser($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$statement = "select * from users where screenname = '$un';";
	$response = $this->logindb->query($statement);
	$row = $response->fetch_assoc();
	if($row > 0)
	{
		return "User already in database.";
	}
	$statement = "insert into users(screenname, password) Values('$un', '$pw');";
	$response = $this->logindb->query($statement);
	return "User Registered";
}
}
?>
