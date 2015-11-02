<?php

namespace model;


class TypeSpending 
{
	private $idTypeSpending;
	private $type;
	private $spending;
	
	public function __construct($idTypeSpending=null, $type=null, $spending=null)
	{
		$this->idTypeSpending = $idTypeSpending;
		$this->type = $type;
		$this->spending = $spending;
	}
	
	public function getIdTypeSpending()
	{
		return $this->idTypeSpending;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getSpending()
	{
		return $this->spending;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>