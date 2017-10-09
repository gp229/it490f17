#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php.inc');

function doLog($logfile,$level,$machine,$message)
{
	$logConn = new logger($logfile);
	$logConn->writeLog($level,$machine,$message);
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
    case "log":
	if(empty($request['logfile']) || empty($request['level']) || empty($request['machine']) || empty($request['message']))
	{
		echo "Logfile, level, machine, or message not set for log.".PHP_EOL;
	}
	doLog($request['logfile'],$request['level'],$request['machine'],$request['message']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","loggingServer");

$server->process_requests('requestProcessor');
exit();
?>

