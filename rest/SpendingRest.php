<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");

require_once(__DIR__."/../model/Spending.php");
require_once(__DIR__."/../database/SpendingDAO.php");

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
    	}
    	
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
$spendingRest = new SpendingRest();
URIDispatcher::getInstance()
	->map("POST", "/spendings", array($spendingRest,"create"));






?>