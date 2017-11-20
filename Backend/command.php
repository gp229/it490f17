<?php
include("../Deployment/deploy.php.inc");
include("flieSave.php");
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerclient.php.inc');


class sendCommand
{
private $command


public function buildBundle($filePath)
{
fileSave($filePath);
echo "deploy new bundle";

}

public function deployInstall($command)
{

}


}
?>
