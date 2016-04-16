<?php

class Revenue
{
	private $idRevenue;
	private $dateRevenue;
	private $quantity;
	private $name;
	private $owner;
	
	public function __construct($idRevenue = NULL, $dateRevenue = NULL, $quantity = NULL, $name = NULL, $owner = NULL)
	{
		$this->idRevenue = $idRevenue;
		$this->dateRevenue = $dateRevenue;
		$this->quantity = $quantity;
		$this->name = $name;
		$this->owner = $owner;
	}
	
	public function getidRevenue()
	{
		return $this->idRevenue;
	}
	
	public function getdateRevenue() 
	{
		return $this->dateRevenue;
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
	
	public function setdateRevenue($dateRevenue)
	{
		$this->dateRevenue = $dateRevenue;
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
