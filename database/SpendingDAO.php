<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Spending.php");
require_once(__DIR__."/../model/Type.php");


class SpendingDAO
{
    private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }
	
    public function findByOwner($owner)
    {
        $stmt = $this->db->prepare("SELECT * FROM SPENDING WHERE owner = ?");
        $stmt->execute(array($owner));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $spendings = array();
        
        foreach ($spendings_db as $spending) {
            array_push($spendings, new Spending($spending["idSpending"],$spending["dateSpending"],
                    $spending["quantity"], $spending["name"],new User($spending["owner"])));
        }
        return $spendings;
    }

    public function findByOwnerAndFilter($owner, $startDate, $endDate)
    {
        $query = "SELECT * FROM SPENDING WHERE owner = ? AND dateSpending BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($owner, $startDate, $endDate));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $spendings = array();
        
        foreach ($spendings_db as $spending) {
            array_push($spendings, new Spending($spending["idSpending"],$spending["dateSpending"],
                    $spending["quantity"], $spending["name"], new User($spending["owner"])));
        }
        return $spendings;
    }



    public function findById($idSpending) 
    {
        $stmt = $this->db->prepare("SELECT * FROM SPENDING WHERE idSpending = ?");
        $stmt->execute(array($idSpending));
        $spending = $stmt->fetch(PDO::FETCH_ASSOC);

        if($spending != NULL) {
            return new Spending($spending["idSpending"],$spending["dateSpending"],
                    $spending["quantity"], $spending["name"], new User($spending["owner"]));
        } else {
            return NULL;
        }



    }

     public function findByIdWithTypes($idSpending)
    {
        $stmt = $this->db->prepare("SELECT * FROM SPENDING INNER JOIN TYPE_SPENDING ON SPENDING.idSpending = TYPE_SPENDING.spending 
            INNER JOIN TYPE ON TYPE_SPENDING.type = TYPE.idType WHERE idSpending = ? ORDER BY name;");
        $stmt->execute(array($idSpending));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (sizeof($spendings_db) > 0) {
            $spending = new Spending($spendings_db[0]["idSpending"],
                $spendings_db[0]["dateSpending"],
                $spendings_db[0]["quantity"],
                new User($spendings_db[0]["owner"]));
            $types_array = array();
            if($spendings_db[0]["idType"] != NULL) {
                foreach ($spendings_db as $type) {
                    $type = new Type($type["idType"],
                        $type["dateType"],
                        $type["name"],
                        $type["owner"]);
                    array_push($types_array, $type);
                }
            }
            $spending->setType($types_array);
            

            return $spending;
        } else {
            return NULL;
        }

        

    }

     public function findByOwnerWithTypes($owner)
    {
        $stmt = $this->db->prepare("SELECT s.idSpending as 'spending.id',
                s.dateSpending as 'spending.date',
                s.quantity as 'spending.quantity',
                s.name as 'spending.name',
                s.owner as 'spending.owner',
                t.idType as 'type.id',
                t.dateType as 'type.date',
                t.name as 'type.name',
                t.owner as 'type.owner'
            FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType 
            WHERE s.owner = ? ");
        $stmt->execute(array($owner));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
    


        if (sizeof($spendings_db) > 0) {
            $spendings = [];
            $spendings_array = [];
            $types_array = [];
            foreach ($spendings_db as $spending_loop) {
                $spending = new Spending($spending_loop["spending.id"],
                    $spending_loop["spending.date"],
                    $spending_loop["spending.quantity"],
                    $spending_loop["spending.name"],
                    new User($spending_loop["spending.owner"]));
                
                array_push($spendings_array, $spending);
                
                if ($spending_loop['type.id'] != NULL) {
                   $type = new Type($spending_loop['type.id'],
                        $spending_loop['type.date'],
                        $spending_loop['type.name'],
                        new User($spending_loop["spending.owner"]),
                        $spending_loop['spending.id']);
                   array_push($types_array, $type);
                }
            }

            $spendings = array_unique($spendings_array);

            $arrayTypesBySpending = [];
            $arrayIdSpending = [];
            foreach ($types_array as $type) {
                array_push($arrayIdSpending, $type->getSpending());
            }
            $arrayIds = array_unique($arrayIdSpending);
            
            $arraySplitTypes = [];
            foreach ($arrayIds as $id) {
                $arrayTypes = [];
                foreach ($types_array as $type) {
                    if ($type->getSpending() == $id) {
                        array_push($arrayTypes, $type);
                    }
                }
                foreach ($spendings as $spending) {
                    if ($spending->getIdSpending() == $id) {
                        $spending->setType($arrayTypes);
                        //array_push($spendings, $spending);
                        break;
                    }
                }
            }

            print_r($spendings);    

            return $spendings;

        } else {
            return NULL;
        }

     
        
        

    }

    
    public function save($spending)
    {
    	$stmt = $this->db->prepare("INSERT INTO SPENDING(quantity,name,owner) VALUES (?,?,?)");
    	$stmt->execute(array($spending->getQuantity(), $spending->getName(),$spending->getOwner()));
    	return $this->db->lastInsertId();
    }
    
    public function update($spending)
    {
    	$stmt = $this->db->prepare("UPDATE SPENDING SET quantity = ?, name = ?,owner = ? WHERE idSpending = ?");
    	$stmt->execute(array($spending->getQuantity(), $spending->getName(), 
            $spending->getOwner()->getLogin(), $spending->getIdSpending()));    	
    }
    
    public function delete($idSpending)
    {
    	$stmt = $this->db->prepare("DELETE FROM SPENDING WHERE idSpending = ?");
    	$stmt->execute(array($idSpending));
    }
    
    
}
?>