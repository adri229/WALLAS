<?php


class Type
{
	private $idType;
	private $name;

	private $owner;

	public function __construct($idType = NULL, $name = NULL, $percent = NULL, $owner = NULL)
	{
		$this->idType = $idType;
		$this->name = $name;
		$this->percent = $percent;
		$this->owner = $owner;
	}

	public function getIdType()
	{
		return $this->idType;
	}

	public function getName()
	{
		return $this->name;
	}


	public function getOwner()
	{
		return $this->owner;
	}

	public function setIdType($idType)
	{
		$this->idType = $idType;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setOwner($owner)
	{
		$this->owner = $owner;
	}
}
?>
