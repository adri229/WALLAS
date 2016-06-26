<?php
/**
 * Clase que proporciona un acceso en orientación a objetos de la
 * variable global $_SERVER, para obtener información del servidor.
 * 
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
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