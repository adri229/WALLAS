<?php
namespace model;

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
    
    public static function findBy($where)
    {
        $ids = database\DAOFactory::getDAO("model")->select(["*"],$where);
        if (!$ids) return array();
        
        $objects = array();
        foreach ($ids as $id) {
            $type = new Type(["idType"]);
            if (!$type->getEntity()) break;
            $objects[] = $type;
        }
        
        return $objects;
    }
    
    
    public function getEntity()
    {
        $rows = $this->dao->selec(["*"],["idType" => $this->idType]);
        if (!$rows) return false;
        
        $this->name   = $rows[0]["name"];
        
        return true;
    }
    
    
    public function save()
    {
        $data = ["name" => $this->name];
        
        if (isset($this->idType))
            return $this->dao->update($data, ["idType" => $this->idType]);
        
        return $this->dao->insert($data);
    }
    
    public function delete()
    {
        return $this->dao->delete(["idType" => $this->idType]);
    }
    
    public function validate()
    {
        
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
