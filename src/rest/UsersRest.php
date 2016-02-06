<?php
	
namespace rest;


/**
 * Refactorizar cuando se cree la clase Server
 * @author acfernandez4
 *
 */
class UsersRest extends BaseRest 
{
	private $user;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function login($username)
	{
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getLogin() != $username) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("You are not authorized to login as anyone but you");
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			echo("Hello ".$username);
		}
	}
	
	public function create($data)
	{
		$required = 
			isset($data->login)		 &&
			isset($data->password)	 &&
			isset($data->verifyPass) &&
			isset($data->fullname)	 &&
			isset($data->email)		 &&
			isset($data->phoneNumber)&&
			isset($dataa->address)	 &&
			isset($data->country);
		
		if (!$required && $data->password !== $data->verifyPass) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
			 
		$this->user = new \model\User(strtolower($data->login));
		if (!$user->isNewLogin() || !$user->setPassword($data->password)) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
		
		$this->user->setFullName($data->fullname);
		$this->user->setEmail($data->email); 
		$this->user->setPhoneNumber($data->phoneNumber);
		$this->user->setAddress($data->address);
		$this->user->setCountry($data->country);
		
		if (!$user->validate() || !$user->save()) {
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
      		header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
			echo(json_encode($e->getErrors()));
		}
		
	}
	
	public function update($data) 
	{
		if (!isset($data->login)) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
		
		$this->user = new \model\User(strtolower($data->login));
		if ($this->user->isNewLogin()) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
		$this->user->getEntity();
		
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getLogin() == $data->login) {
			 header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
     		 echo("You are not authorized to login as anyone but you");
		}
		
		$this->updatePost($data);
	}
	
	private function updatePost($data)
	{
		if (!empty($data->password) && ($data->password !== $data->verifyPass)) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
		
		if (!empty($data->password) && ($this->user->checkPassword($data->password))) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo(json_encode($e->getErrors()));
		}
		
		if (!empty($data->fullname))
			$this->user->setFullname();
		
		if (!empty($data->email))
			$this->user->setEmail();
		
		if (!empty($data->phoneNumber))
			$this->user->setPhoneNumber();
		
		if (!empty($data->address))
			$this->user->setAddress();
		
		if (!empty($data->country))
			$this->user->setCountry();
		
		if (!$user->validate() || !$user->save()) {
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
			echo(json_encode($e->getErrors()));
		}
	}
	
	public function delete($username) 
	{
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getLogin() == $username) {
			$this->user = new \model\User(strtolower($username));
			if ($this->user->delete()) {
				header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
			} else {
				header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
			}
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("You are not authorized to login as anyone but you");
		}
		
	}
	
	
	//Muestra los detalles de un usuario
	public function get() 
	{
		$currentLogged = parent::authenticateUser();
		if (!empty($currentLogged)) {
			$this->user = new \model\User(strtolower($currentLogged));
			$this->user->getEntity();
			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("You are not authorized to login as anyone but you");
		}
	}
}

$usersRest = new UsersRest();
\URIDispatcher::getInstance()
	->map("GET", "/users/$1", array($usersRest, "login"))
	->map("POST", "/users", array($usersRest, "create"))
	->map("PUT", "/users/$1", array($usersRest, "update"))
	->map("DELETE", "/users/$1", array($usersRest, "delete"))
	->map("GET", "/users/$1", array($usersRest, "get"));

?>