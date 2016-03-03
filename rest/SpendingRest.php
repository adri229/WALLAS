<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");

require_once(__DIR__."/../model/Spending.php");
require_once(__DIR__."/../database/SpendingDAO.php");


require_once(__DIR__."/../rest/BaseRest.php");

class SpendingRest extends BaseRest 
{
	private $spendingDAO;
	
	public function __construct()
	{
        parent::__construct();
        $this->spendingDAO = new SpendingDAO();
	}
	
	
	 public function create($data)
    {
    	$currentUser = parent::authenticateUser();
    	$spending = new Spending();

    	if (isset($data->date) && isset($data->quantity)) {
    		$spending->setDateSpending($data->date);
    		$spending->setQuantity($data->quantity);
    		$spending->setOwner($currentUser->getLogin());
    	
    	
	    	try {
	    		//$spending->validate();	
	    		$idSpending = $this->spendingDAO->save($spending);
	    		header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
	      		header('Location: '.$_SERVER['REQUEST_URI']."/".$idSpending);
	      		header('Content-Type: application/json');

	    	} catch (ValidationException $e) {
	    		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
	      		echo(json_encode($e->getErrors()));
	    	}
	    }
	}



	public function update($idSpending, $data)
	{
		$currentUser = parent::authenticateUser();

		$spending = $this->spendingDAO->findById($idSpending);
		if ($spending == NULL) {
      		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
      		echo("Spending with id ".$idSpending." not found");
      		return;
    	}


    	if($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
      		echo("you are not the owner of this spending");
      		return;
    	}

    	if (isset($data->quantity)) {
    		$spending->setQuantity($data->quantity);

    		try {
      			// validate Post object
      			//$spending->validate(); // if it fails, ValidationException
      			$this->spendingDAO->update($spending);
      			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
    		}catch (ValidationException $e) {
      			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
      			echo(json_encode($e->getErrors()));
    		}
    	}


	}


	public function delete($idSpending)
	{
		$currentUser = parent::authenticateUser();
		
		$spending = $this->spendingDAO->findById($idSpending);
		if ($spending == NULL) {
      		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
      		echo("Spending with id ".$idSpending." not found");
      		return;
    	}


    	if($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
      		echo("you are not the owner of this spending");
      		return;
    	}

    	try {
      		$this->spendingDAO->delete($idSpending);
      		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
    	}catch (ValidationException $e) {
      		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
      		echo(json_encode($e->getErrors()));
    	}	


	}


	public function getByOwner($owner)
	{
		$currentUser = parent::authenticateUser();

		$spendings = $this->spendingDAO->findByOwner($owner);
		$spending_array = array();
		foreach ($spendings as $spending) {
			array_push($spending_array, array(
				"idSpending" => $spending->getIdSpending(),
				"dateSpending" => $spending->getDateSpending(),
				"quantity" => $spending->getQuantity(),
				"owner" => $spending->getOwner()->getLogin()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($spending_array));
	}



}

$spendingRest = new SpendingRest();
URIDispatcher::getInstance()
	->map("GET", "/spendings/$1", array($spendingRest, "getByOwner"))	
	->map("POST", "/spendings", array($spendingRest,"create"))
	->map("PUT", "/spendings/$1", array($spendingRest, "update"))
	->map("DELETE", "/spendings/$1", array($spendingRest, "delete"));






?>