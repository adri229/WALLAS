<?php

namespace model;

class Spending extends Model{
	
	private $idSpending;
	private $dateSpending;
	private $quantity;
	private $owner;
	
	public function __construct($idSpending=null, $owner=null) {
	    parent::__construct();
	    
        $this->idSpending = $idSpending;
        $this->dateSpending = null;
        $this->quantity = null;
        $this->owner = $owner;
    }
        
    public function findBy($where)
    {
        $ids = \database\DAOFactory::getDAO("spending")->select(["idSpending"],$where);
        if (!$ids) return array();
        
        $objects = array();
        foreach ($ids as $id) {
            $spending = new Spending($id["idSpending"]);
            if (!$spending->getEntity()) break;
            $objects[] = $spending;
        }
        return $objects;
    }
        
    
    public function getEntity()
    {
        $rows = $this->dao->select(["*"], ["idSpending" => $this->idSpending]);
        if (!$rows) return false;
        
        $this->dateSpending = $rows[0]["dateSpending"];
        $this->quantity     = $rows[0]["quantity"];
        $this->owner        = $rows[0]["owner"];
        
        return true;
    }
    
    public function save()
    {
        $data = [ 
            "dateSpending" => $this->dateSpending,
            "quantity"     => $this->quantity,
            "owner"        => $this->owner 
        ];
        
        if (isset($this->idSpending))
            return $this->dao->update($data, ["idSpending" => $this->idSpending]);
        
        return $this->dao->insert($data);
    }
    
    public function delete()
    {
        return $this->dao->delete(["idSpending" => $this->idSpending]);
    }
    
    public function validate()
    {
        
    }
    
    
    public function getIdSpending()
    {
        return $this->idSpending;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
    
    
    
    
}
?>
