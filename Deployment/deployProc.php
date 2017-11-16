#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('deploy.php.inc');

function doMakeBundle($path,$serverType,$ip)
{
	$deployConn = new deployDB();
	$response = $deployConn->newBundle($path,$serverType,$ip);
	echo $response.PHP_EOL;
	return $response;	
}

function doInstallBundle($cluster,$server,$version)
{
	$deployConn = new deployDB();
	$response = $deployConn->installBundle($cluster,$server,$version);
	echo $response.PHP_EOL;
	return $response;	
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
    case "new":
	if(empty($request['path']) || empty($request['serverType']) || empty($request['ip']))
	{
		echo "Path, serverType, or ip not set for new package.".PHP_EOL;
	}
	doMakeBundle($request['path'],$request['serverType'],$request['ip']);
    case "install":
	if(empty($request['cluster']) || empty($request['server']) || empty($request['version']))
	{
		echo "Cluster, server, or version not set for install.".PHP_EOL;
	}
	doInstallBundle($request['cluster'],$request['server'],$request['version']);
    case "rollback":
	if(empty($request['logfile']) || empty($request['level']) || empty($request['machine']) || empty($request['ip']) || empty($request['message']))
	{
		echo "Logfile, level, machine, ip, or message not set for log.".PHP_EOL;
	}
	doLog($request['logfile'],$request['level'],$request['machine'],$request['ip'],$request['message']);
    case "deprecate":
	if(empty($request['logfile']) || empty($request['level']) || empty($request['machine']) || empty($request['ip']) || empty($request['message']))
	{
		echo "Logfile, level, machine, ip, or message not set for log.".PHP_EOL;
	}
	doLog($request['logfile'],$request['level'],$request['machine'],$request['ip'],$request['message']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","deploymentServer");

$server->process_requests('requestProcessor');
exit();
?>

