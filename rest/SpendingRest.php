<?php


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
	
	}
	
	
}
