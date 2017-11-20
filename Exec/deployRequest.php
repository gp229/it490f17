#!/usr/bin/php
<?php
echo "Do you want to make, install, deprecate, or rollback package? ";
$handle = fopen ("php://stdin","r");
$request['type'] = fgets($handle);
$request['type'] = trim($request['type']);
switch ($request['type']) {
    case "make":
        echo "Creating new bundle".PHP_EOL;
	$serverInfo = getHostInfo();
  	$server =  $serverInfo['server']['serverName'];
	$now = date('Y-m-d_H:i:s',time());
	$path = "~/git/it490f17/"
	if($server != "Backend" || $server != "Frontend" || $server != "DMZ")
	{
		echo $server." is not a valid server type".PHP_EOL;
		exit();
	}
	shell_exec("tar -czf ".$path.$server."_".$now." ".$path.$server" ".$path."libraries");	
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
