#!usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('loggerClient.php.inc');

 
  $home = getHostInfo();
  $clus = $home['server']['cluster'];
  $servName =  $home['server']['serverName'];

function execInstall($path)
{
	$response = exec("./connect $path");
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

  	if($request['cluster'] != $clus && $request['serverType'] != $servName)
  	{
		return "Passing By: Not the cluster and/or server for use";
  	}

   	switch ($request['type'])
  	{
    		case "install":
			if(empty($request['path'])
			{
				echo "Version number not set".PHP_EOL;
				return "Version number not set";
			}
			execInstall($request['path']);
   		case "rollback":
			execRollback();
  	}

  	return array("returnCode" => '0', 'message' => "Server recieved request and process");
}

$server = new rabbitMQServer("testRabbitMQ.ini","execServer");

$server->process_requests('requestProcessor');
exit();
?>
