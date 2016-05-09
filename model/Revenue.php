<?php

class Revenue
{
	private $idRevenue;
	private $date;
	private $quantity;
	private $name;
	private $owner;
	
	public function __construct($idRevenue = NULL, $date = NULL, $quantity = NULL, $name = NULL, $owner = NULL)
	{
		$this->idRevenue = $idRevenue;
		$this->date = $date;
		$this->quantity = $quantity;
		$this->name = $name;
		$this->owner = $owner;
	}
	
	public function getidRevenue()
	{
		return $this->idRevenue;
	}
	
	public function getDate() 
	{
		return $this->date;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getName() {
		return $this->name;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setidRevenue($idRevenue)
	{
		$this->idRevenue = $idRevenue;
	}
	
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}

	public function setName($name) {
		$this->name = $name;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}



}
?>
