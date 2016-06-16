<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");

require_once(__DIR__."/../model/Type.php");
require_once(__DIR__."/../database/TypeDAO.php");
require_once(__DIR__."/../database/TypeSpendingDAO.php");

require_once(__DIR__."/../rest/BaseRest.php");


class TypeRest extends BaseRest
{
	private $typeDAO;
	private $TypeSpendingDAO;

	function __construct()
	{
		parent::__construct();
		$this->typeDAO = new TypeDAO();		
		$this->typeSpendingDAO = new TypeSpendingDAO();
	}

	public function create($data)
	{
		$currentUser = parent::authenticateUser();
		$type = new Type();

		if (!$this->typeDAO->isNewType($data->name)) {
	       	header($this->server->getServerProtocol() .' 400 Bad request');
	    	echo("This type already exits");
		    return;
        }

		if(isset($data->name)) {
			$type->setName($data->name);
			$type->setOwner($currentUser->getLogin());

			try {
				$idType = $this->typeDAO->save($type);
	    		header($this->server->getServerProtocol().' 201 Created');
	      		header($this->server->getRequestUri()."/".$idType);
	      		header('Content-Type: application/json');
			} catch (ValidationException $e) {
	    		header($this->server->getServerProtocol().' 400 Bad request');
	      		echo(json_encode($e->getErrors()));
	    	}
		}
	}

	public function update($idType, $data)
	{	
		$currentUser = parent::authenticateUser();

		$type = $this->typeDAO->findById($idType);
		if ($type == NULL) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo("Type with id ".$idType." not found");
      		return;
    	}

    	if (!$this->typeDAO->isNewType($data->name)) {
	       	header($this->server->getServerProtocol() .' 400 Bad request');
	    	echo("This type already exits");
		    return;
        }

    	if($type->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($this->server->getServerProtocol().' 403 Forbidden');
      		echo("You are not the owner of this type");
      		return;
    	}

    	if (isset($data->name)) {
			$type->setName($data->name);

    		try {
      			//$type->validate(); // if it fails, ValidationException
      			$this->typeDAO->update($type);
      			header($this->server->getServerProtocol().' 200 Ok');
    		}catch (ValidationException $e) {
      			header($this->server->getServerProtocol().' 400 Bad request');
      			echo(json_encode($e->getErrors()));
    		}
    	}
	}

	public function delete($idType)
	{
		$currentUser = parent::authenticateUser();

		$type = $this->typeDAO->findById($idType);
		if ($type == NULL) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo("Type with id ".$idType." not found");
      		return;
    	}

    	if($type->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($this->server->getServerProtocol().' 403 Forbidden');
      		echo("You are not the owner of this type");
      		return;
    	}

    	try {
    		//$this->typeSpendingDAO->deleteByType($idType);
      		$this->typeDAO->delete($idType);
      		header($this->server->getServerProtocol().' 200 Ok');
    	}catch (ValidationException $e) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo(json_encode($e->getErrors()));
    	}	
	}

	public function getByOwner($owner)
	{
		$currentUser = parent::authenticateUser();

		$types = $this->typeDAO->findByOwner($owner);
		if ($types == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("The defined interval time not contains Spendings");
            return;
        }

        foreach ($types as $type) {
            if ($type->getOwner()->getLogin() != $currentUser->getLogin()) {
                header($this->server->getServerProtocol() . ' 403 Forbidden');
                echo("you are not the owner of this type");
                return;
            }
        }

		$type_array = array();
		foreach ($types as $type) {
			array_push($type_array, array(
				"idType" => $type->getIdType(),
				"name" => $type->getName(),
				"owner" => $type->getOwner()->getLogin()
			));
		}

		header($this->server->getServerProtocol().' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($type_array));
	}

}

$typeRest = new TypeRest();
URIDispatcher::getInstance()
	->map("GET", "/types/$1", array($typeRest, "getByOwner"))	
	->map("POST", "/types", array($typeRest,"create"))
	->map("PUT", "/types/$1", array($typeRest, "update"))
	->map("DELETE", "/types/$1", array($typeRest, "delete"));


?>