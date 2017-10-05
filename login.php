<?php
require_once('loginClient.php.inc');

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
echo json_encode($response);
exit(0);
?>
