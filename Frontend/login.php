<?php
require_once('loginClient.php.inc');
require_once('loggerClient.php.inc');

try{
	if (empty($_POST))
	{
		$msg = "NO POST MESSAGE SET";
		echo json_encode($msg);
		exit(0);
	}
	$response = "unsupported request type";

	//POST returns keys: login or register, uname, and pword.
	$request = $_POST;
	
	//Calls that functions to make the Client
	$myClient = new rabbitClient();
	$response = $myClient->make_request($request);

	/*if($response == "LoginSuccess")
	  {
	  header('Location: main.php'); 
	  exit(0);
	  }*/
}
catch(Exception $e)
{
	$mylogger = new loggerClient();
	$mylogger->sendLog("userauth.log",2,"Error with user authentication: ".$e." in ".__FILE__." on line ".__LINE__);
	$response = "Sorry, something went wrong.";
}

echo json_encode($response);
exit(0);
?>
