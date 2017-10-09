<?php
class logger
{
	private $filestream;
	
	public function __construct($logfile)
	{
		$logdirectory = "/var/log/stocks/";
		$this->filestream = fopen($logdirectory.$logfile,"a") or exit("Can't open log file ".$logfile.PHP_EOL);
	}
	
	public function writeLog($level,$machine,$message)
	{
		if($level <= 1)
		{
			$level = "LOW";
		}
		else if($level == 2)
		{
			$level = "MEDIUM";
		} 
		else if($level == 3)
		{
			$level = "HIGH";
		} 
		else if($level >= 4)
		{
			$level = "SEVERE";
		} 
		fwrite($this->filestream, "[".date('Y-m-d H:i:s')."] ==".$level."== Server ". $machine.PHP_EOL.$message.PHP_EOL);
	}
	
	public function __destruct()
	{
		fclose($this->filestream);
	}
}

?>
