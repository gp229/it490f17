<?php
class logger
{
	private $filestream;
	
	public function __construct($logfile)
	{
		$logdirectory = "/var/log/stocks/";
		$this->filestream = fopen("$logdirectory".$logfile,"a") or exit("Can't open log file ".$logfile.PHP_EOL);
	}
	
	public function writeLog($message)
	{
		fwrite($this->filestream, "[".date('Y-m-d H:i:s')."] ".$message.PHP_EOL);
	}
	
	public function __destruct()
	{
		fclose($this->filestream);
	}
}

?>
