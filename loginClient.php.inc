<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

class rabbitClient
{
	public $client;
	public function __construct()
	{
		$this->client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	}
	public function make_request($request)
	{
		$client = $this->client;
		return $client->send_request($request);
	}
}
?>
