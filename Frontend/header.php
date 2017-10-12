<?php
require_once('loggerClient.php.inc');
session_start();
try{
	if(!isset($_SESSION['loginUser']))
	{
		header('Location: index.html');
		exit(0);
	}
	echo "Hello, ".$_SESSION['loginUser'];
}
catch(Error $e)
{
	$mylogger = new loggerClient();
	$mylogger->sendLog("userauth.log",2,"Error with user authentication: ".$e." in ".__FILE__." on line ".__LINE__);
	$response = "Sorry, something went wrong.";
}
?>
