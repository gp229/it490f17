#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
	return "I logged in";  
}
function doRegister($username,$password)
{
	return "New Account";   //return false if not valid
}


function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
   switch ($request['type'])
  {
    case "login":
	if(empty($request['uname']) || empty($request['pword']))
	{
		return "Username or Password not given.";
	}
	return doLogin($request['uname'],$request['pword']);
    case "register":
	if(empty($request['uname']) || empty($request['pword']))
	{
		return "Username or Password not given.";
	}
	return doRegister($request['uname'],$request['pword']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

