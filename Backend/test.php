#!/usr/bin/php
<?php

require_once("get_host_info.inc");
$ini = array("db.ini");
$test = getHostInfo($ini);
var_dump($test);
?>
