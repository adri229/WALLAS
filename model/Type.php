<?php


class Type 
{
	private $idType;
	private $name;
	
	public function __construct($idType = NULL, $name = NULL)
	{
		$this->idType = $idType;
		$this->name =$name;
	}
	
	public function getIdType()
	{
		return $this->idType;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setIdType($idType)
	{
		$this->idType = $idType;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	
	
}