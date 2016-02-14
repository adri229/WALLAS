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
            
            
	}
	
	
}
