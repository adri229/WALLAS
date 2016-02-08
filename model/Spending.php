<?php

class Spending 
{
	private $idSpending;
	private $dateSpending;
	private $quantity;
	private $owner;
	
	public function __construct($idSpending = NULL, $dateSpending = NULL, $quantity = NULL, $owner = NULL)
	{
		$this->idSpending = $idSpending;
		$this->dateSpending = $dateSpending;
		$this->quantity = $quantity;
		$this->owner = $owner;
	}
	
	public function getIdSpending()
	{
		return $this->idSpending;
	}
	
	public function getDateSpending() 
	{
		return $this->dateSpending;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	
	public function setIdSpending($idSpending)
	{
		$this->idSpending = $idSpending;
	}
	
	public function setDateSpending($dateSpending)
	{
		$this->dateSpending = $dateSpending;
	}
	
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
	
	
	
	
	
	
	
	
	
	
}
?>