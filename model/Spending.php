<?php

class Spending 
{
	private $idSpending;
	private $dateSpending;
	private $quantity;
	private $owner;
	private $type;
	
	public function __construct($idSpending = NULL, $dateSpending = NULL, $quantity = NULL, $owner = NULL, $type = NULL)
	{
		$this->idSpending = $idSpending;
		$this->dateSpending = $dateSpending;
		$this->quantity = $quantity;
		$this->owner = $owner;
		$this->type = $type;
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
	
	public function getType()
	{
		return $this->type;
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
	
	public function setType(array $type)
	{
		$this->type = $type;
	}
}
?>