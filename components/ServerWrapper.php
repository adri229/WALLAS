<?php
/**
* 
*/
class ServerWrapper
{

	private $server;
	
	function __construct()
	{
		
	}

	public function getPhpAuthUser()
	{
		return $_SERVER['PHP_AUTH_USER'];
	}


	public function getPhpAuthPw()
	{
		return $_SERVER['PHP_AUTH_PW'];
	}

	public function getServerProtocol()
	{
		return $_SERVER['SERVER_PROTOCOL'];
	}

	public function getRequestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}
?>