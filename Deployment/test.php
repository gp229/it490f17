#!/usr/php

<?php

$now = date('Y-m-d_H:i:s',time());
$file = "d32weqeasfsadf".$now;
echo $file.PHP_EOL;
$file = substr($file, 0, -19);
echo $file;
?>
