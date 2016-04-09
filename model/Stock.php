<?php

class Stock
{
	private $idStock;
	private $dateStock;
	private $total;
	private $owner;
	
	public function __construct($idStock = NULL, $dateStock = NULL, $total = NULL, $owner =NULL)
	{
		$this->idStock = $idStock;
		$this->dateStock = $dateStock;
		$this->total = $total;
		$this->owner = $owner;
	}
	
	public function getIdStock()
	{
		return $this->idStock;
	}
	
	public function getDateStock()
	{
		return $this->dateStock;
	}
	
	public function getTotal()
	{
		return $this->total;
	}
	
	public function getOwner()
	{
		return $this->owner;
	}
	
	public function setIdStock($idStock)
	{
		$this->idStock = $idStock;
	}
	
	public function setDateStock($dateStock)
	{
		$this->dateStock = $dateStock;
	}
	
	public function setTotal($total)
	{
		$this->total = $total;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
}
?>