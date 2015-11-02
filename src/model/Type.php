<?php
namespace model;

use database;
class Type extends Model
{

    private $idType;

    private $name;

    public function __construct($idType = NULL, $name = NULL)
    {
        parent::__construct();
        
        $this->idType = idType;
        $this->name = name;
    }
    
    public function findBy($where)
    {
        $ids = database\DAOFactory::getDAO("model")->select(["*"],$where);
        if (!$ids) return array();
        
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    

    public function getidType()
    {
        return $this->idType;
    }

    public function getname()
    {
        return $this->name;
    }

   
}
?>
