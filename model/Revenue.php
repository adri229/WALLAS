<?php

/**
 * Modelo de ingresos
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class Revenue
{
	private $idRevenue;
	private $date;
	private $quantity;
	private $name;
	private $owner;
	private $types;

	public function __construct($idRevenue = NULL, $date = NULL, $quantity = NULL, $name = NULL, $owner = NULL)
	{
		$this->idRevenue = $idRevenue;
		$this->date = $date;
		$this->quantity = (float) $quantity;
		$this->name = $name;
		$this->owner = $owner;
		$this->types = [];
	}

	public function getIdRevenue()
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

	public function getTypes()
	{
		return $this->types;
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
		$this->quantity = (float) $quantity;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

	public function addType($type)
	{
		array_push($this->types, $type);
	}
}
?>
