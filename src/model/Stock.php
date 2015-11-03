<?php
namespace model;

class Stock extends Model
{

    private $idStock;

    private $revenue;

    private $dateStock;
    
    private $total;

    private $owner;

    public function __construct($idStock = NULL, $revenue = NULL, $dateStock = NULL, $total = NULL, $user = NULL)
    {
        parent::__construct();
        
        $this->idStock = idStock;
        $this->revenue = revenue;
        $this->dateStock = dateStock;
        $this->total = total;
        $this->owner = user;
    }
    
    
    public static function findBy($where)
    {
        $ids = database\DAOFactory::getDAO("stock")->select(["idStock"],$where);
        if (!$ids) return array();
        
        $objects = array();
        foreach ($ids as $id){
           $stock = new Stock($id["idStock"]);
           if (!$stock->getEntity()) break;
           $objects[] = $stock;
        }
        
        return $objects;
    }
    
    
    public function getEntity()
    {
        $rows = $this->dao->select(["*"],["idStock" => $this->idStock]);
        if (!$rows) return false;
        
        $this->revenue   = $rows[0]["revenue"];
        $this->dateStock = $rows[0]["dateStock"]; 
        $this->total     = $rows[0]["total"];   
        $this->owner     = $rows[0]["owner"];
        
        return true;
    }
    
    
    public function save()
    {
        $data = [
            "revenue"   => $this->revenue,
            "dateStock" => $this->dateStock,
            "total"     => $this->total,
            "owner"     => $this->owner
        ];
        
        if (isset($this->idStock))
            return $this->dao->update($data, ["idStock" => $this->idStock]);
        
        return $this->dao->insert($data);
        
    }
    
    public function delete()
    {
        return $this->dao->delete(["idStock" => $this->idStock]);
    }
    
    
    public function validate()
    {
        
        
    }
    
    
    public function getIdStock()
    {
        return $this->idStock;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }
}
?>
