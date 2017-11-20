<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('loggerClient.php.inc');

public function fileSave($remoteFile)
{
$fileTempName = $_FILES[$remoteFile]['tmp_name'];
$fileSize = $_FILES[$remoteFile]['size'];
$fileName = "Uploaded Files\\".$_FILES[$remoteFile]['name'];

if (file_exists($fileName))
	{
		$fWriteHandle = fopen($fileName,'w');
	}
	else
	{
		$fwriteHandle = fopen($fileName,'w');
	}
$fReadHandle = fopen($fileTempName, 'rb');
$fileContent = fread($fReadHandle,$fileSize);
fwrite($fWriteHandle,$fileContent);
fclose($fWriteHandle);
echo "Files saved successfully: ".$_FILES[$remoteFile]['name'];
}



?>

