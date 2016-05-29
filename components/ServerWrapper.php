<?php
/**
* 
*/
class ServerWrapper
{

	private $server;
	
	function __construct()
	{
		$this->server = $_SERVER;
	}

	public function getPhpAuthUser()
	{
		return $this->server['PHP_AUTH_USER'];
	}


	public function getPhpAuthPw()
	{
		return $this->server['PHP_AUTH_PW'];
	}

	public function getServerProtocol()
	{
		return $this->server['SERVER_PROTOCOL'];
	}

	public function getRequestUri()
	{
		return $this->server['REQUEST_URI'];
	}
}
?>