<?php
session_start();
$_SESSION['dog'] = "jimmy";
echo $_SESSION['id'];
var_dump($_SESSION);
echo session_id();
?>
