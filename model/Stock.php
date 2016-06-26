<?php

/**
 * Modelo de saldos
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class Stock
{
	private $idStock;
	private $date;
	private $total;
	private $owner;
	
	public function __construct($idStock = NULL, $date = NULL, $total = NULL, $owner =NULL)
	{
		$this->idStock = $idStock;
		$this->date = $date;
		$this->total = $total;
		$this->owner = $owner;
	}
	
	public function getIdStock()
	{
		return $this->idStock;
	}
	
	public function getDate()
	{
		return $this->date;
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
	
	public function setDate($date)
	{
		$this->date = $date;
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