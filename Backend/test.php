<?php
$joe['stocks']['goog'] = array("kappa" => "ok", "fasdf" => "23");
$joe['stocks']['amz'] = array("kappa" => "ok", "fasdf" => "23");
var_dump($joe);
foreach( $joe['stocks']['goog'] as $key => $value)
{
	echo $key.$value;
}
?>
