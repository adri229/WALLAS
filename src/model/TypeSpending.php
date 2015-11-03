<?php

namespace model;

class TypeSpending extends Model
{
	private $idTypeSpending;
	private $type;
	private $spending;
	
	public function __construct($idTypeSpending=null, $type=null, $spending=null)
	{
	    parent::__construct();
	    
		$this->idTypeSpending = $idTypeSpending;
		$this->type = $type;
		$this->spending = $spending;
	}
	
	public static function findBy($where)
	{
	    $ids = database\DAOFactory::getDAO("typespeding")->select(["idTypeSpending"],$where);
	    if (!$ids) return array();
	    
	    $object = array();
	    foreach ($ids as $id) {
	        $typeSpending = new TypeSpending(["idTypeSpending"]);
	        if (!$typeSpending->getEntity()) break;
	        $object = $typeSpending;
	    }
	    
	    return array();
	}
	
	
	public function getEntity()
	{
	    $rows = $this->dao->select(["*"],["idTypeSpending" => $this->idTypeSpending]);
	    if (!$rows) return false;
	    
	    $this->spending = $rows[0]["spending"];
	    $this->type     = $rows[0]["type"];
	    
	    return true;
	}
	
	public function save()
	{
	    $data = ["spending" => $this->spending, "type" => $this->type];
	    
	    if (isset($this->idTypeSpending))
	        return $this->dao->update($data,["idTypeSpending" => $this->idTypeSpending]);
	    
	    return $this->dao->insert($data);
	}
	
	public function delete()
	{
	    return $this->dao->delete(["idTypeSpending" => $this->idTypeSpending]);
	}
	
	public function validate()
	{
	    
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