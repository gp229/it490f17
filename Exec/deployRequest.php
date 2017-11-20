#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('loggerClient.php.inc');
require_once('requestClient.php.inc');

echo "Do you want to make, install, deprecate, or rollback package? ";
$handle = fopen ("php://stdin","r");
$request['type'] = fgets($handle);
$request['type'] = trim($request['type']);
$requestClient = new rabbitClient("testRabbitMQ.ini", 'deploymentServer');
switch ($request['type']) {
    case "make":
        echo "Creating new bundle".PHP_EOL;
        $serverInfo = getHostInfo();
        $server =  $serverInfo['server']['serverName'];
        $now = date('Y-m-d_H-i-s',time());
        $rootpath = "~/git/it490f17/";
        $servertypes = array("Backend", "Frontend", "DMZ");
        if(!in_array($server, $servertypes))
        {
                echo $server." is not a valid server type".PHP_EOL;
                exit();
        }
	$filename = $server."_".$now.".tar.gz"; 
	$localpath = $rootpath.$filename;	
	$destinationPath = $rootpath."Deployment/packages/".$server."/".$filename;

	echo "Getting IP address for deployment Server".PHP_EOL;
	$request['type'] = "getIP";
	$response = $requestClient->make_request($request);
	echo $response.PHP_EOL;

	echo "Executing tar command for".$filename.PHP_EOL;
        $server = escapeshellarg($server);
        $now = escapeshellarg($now);
        $filename = escapeshellarg($filename);
	shell_exec("tar -czf ../'$filename' -C ../ '$server' libraries");
	
	echo "Executing scp".PHP_EOL;
        $shellpath = escapeshellarg($destinationPath);
        $response = escapeshellarg($response);
        $localpath = escapeshellarg($localpath);
	shell_exec("scp '$localpath' '$response':~/git/it490f17/temp/.");
	$request['type'] = "make";
	break;
    case "install":
        echo "Sending";
        break;
    case "deprecate":
        echo "Your favorite color is green!";
        break;
    case "rollback":
        echo "Your favorite color is green!";
        break;
    default:
        echo "Not a valid command".PHP_EOL;
        exit();
}

?>

