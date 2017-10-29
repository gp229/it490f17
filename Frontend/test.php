<?php
require_once('path.inc');
require_once('requestClient.php.inc');
require_once('loggerClient.php.inc');
try{

	$request['type'] = "getUserStocks";
        $request['username'] = 'gabriel';//$_SESSION['loginUser'];
	$myClient = new rabbitClient("testRabbitMQ.ini","stockServer");
   	$response = $myClient->make_request($request);
	var_dump($response);
        
        $request['type'] = "myStockStats";
	$myClient = new rabbitClient("testRabbitMQ.ini","stockServer");
        $response2 = $myClient->make_request($request);
        var_dump($response2);   
}
catch(Error $e)
{
        $mylogger = new loggerClient();
        $mylogger->sendLog("userauth.log",2,"Error with user authentication: ".$e." in ".__FILE__." on line ".__LINE__);
        $response = "Sorry, something went wrong.";
}
?>
