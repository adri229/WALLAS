<?php

/**
 * Modelo de la relación ingreso-tipo
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class TypeRevenue
{
	private $idTypeRevenue;
	private $type;
	private $revenue;
	
	public function __construct($idTypeRevenue = NULL, $type = NULL, $revenue = NULL)
	{
		$this->idTypeRevenue = $idTypeRevenue;
		$this->revenue = $revenue;
		$this->type = $type;
	}
	
	public function getIdTypeRevenue()
	{
		return $this->idTypeRevenue;	
	}
	
	public function getRevenue()
	{
		return $this->revenue;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setIdTypeRevenue($idTypeRevenue)
	{
		$this->idTypeRevenue = $idTypeRevenue;
	}
	
	public function setRevenue($revenue)
	{
		$this->revenue = $revenue;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
}
?>