#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('deploy.php.inc');

function doNewBundle($path,$serverType)
{
	$deployConn = new deployDB();
	$response = $deployConn->newBundle($path,$serverType);
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

function doDeprecateVersion($server,$version)
{
	$deployConn = new deployDB();
	$response = $deployConn->deprecateVersion($server,$version);
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
    case "make":
	if(empty($request['path']) || empty($request['server']))
	{
		echo "Path or serverType not set for new package.".PHP_EOL;
	}
	doNewBundle($request['path'],$request['server']);
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
	if(empty($request['server']) || empty($request['version']))
	{
		echo "Server or version not set for deprecate.".PHP_EOL;
	}
	doDeprecateVersion($request['server'],$request['version']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","deploymentServer");

$server->process_requests('requestProcessor');
exit();
?>

