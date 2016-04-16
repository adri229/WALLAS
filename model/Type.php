<?php


class Type 
{
	private $idType;
	private $dateType;
	private $name;
	private $owner;
	private $spending;
	
	public function __construct($idType = NULL, $dateType = NULL, $name = NULL, $owner = NULL, $spending = NULL)
	{
		$this->idType = $idType;
		$this->dateType = $dateType;
		$this->name = $name;
		$this->owner = $owner; 
		$this->spending = $spending;
	}
	
	public function getIdType()
	{
		return $this->idType;
	}

	public function getDateType()
	{
		return $this->dateType;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function getOwner()
	{
		return $this->owner;
	}

	public function getSpending()
	{
		return $this->spending;
	}
	
	public function setIdType($idType)
	{
		$this->idType = $idType;
	}

	public function setDateType($dateType)
	{
		$this->dateType = $dateType;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
	
	public function setSpending($spending)
	{
		$this->spending = $spending;
	}
}
?>