<?php

class TypeSpending
{
	private $idTypeSpending;
	private $type;
	private $spending;
	
	public function __construct($idTypeSpending = NULL, $type = NULL, $spending = NULL)
	{
		$this->idTypeSpending = $idTypeSpending;
		$this->spending = $spending;
		$this->type = $type;
	}
	
	public function getIdSpending()
	{
		return $this->idTypeSpending;	
	}
	
	public function getSpending()
	{
		return $this->spending;
	}
	
	public function getType()
	{
		return $this->type = $type;
	}
	
	public function setIdTypeSpending($idTypeSpending)
	{
		$this->idTypeSpending = $idTypeSpending;
	}
	
	public function setSpending($spending)
	{
		$this->spending = $spending;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>