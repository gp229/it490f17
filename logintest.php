#!/usr/bin/php
<?php

require_once("login.php.inc");

$login = new logindb();

$output = $login->validateLogin("test","12345");

if ($output)
{
	echo "login successful".PHP_EOL;
}
else
{
	echo "login failed".PHP_EOL;
}
?>
