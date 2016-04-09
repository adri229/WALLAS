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
                    $spending["quantity"], new User($spending["owner"])));
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
                    $spending["quantity"], new User($spending["owner"])));
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
                    $spending["quantity"], new User($spending["owner"]));
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
        $stmt = $this->db->prepare("SELECT * FROM SPENDING INNER JOIN TYPE_SPENDING ON SPENDING.idSpending = TYPE_SPENDING.spending 
            INNER JOIN TYPE ON TYPE_SPENDING.type = TYPE.idType WHERE SPENDING.owner = ? ORDER BY name;");
        $stmt->execute(array($owner));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

        if (sizeof($spendings_db) > 0) {
            $spendings_array = [];
            foreach ($spendings_db as $spending) {
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
                array_push($spendings_array, $spending);

            }
           
            return $spendings_array;
        } else {
            return NULL;
        }

        

    }
    
    public function save($spending)
    {
    	$stmt = $this->db->prepare("INSERT INTO SPENDING(dateSpending,quantity,owner) VALUES (?,?,?)");
    	$stmt->execute(array($spending->getDateSpending(), $spending->getQuantity(), $spending->getOwner()));
    	return $this->db->lastInsertId();
    }
    
    public function update($spending)
    {
    	$stmt = $this->db->prepare("UPDATE SPENDING SET dateSpending = ?, quantity = ?, owner = ? WHERE idSpending = ?");
    	$stmt->execute(array($spending->getDateSpending(), $spending->getQuantity(), $spending->getOwner()->getLogin(), $spending->getIdSpending()));    	
    }
    
    public function delete($idSpending)
    {
    	$stmt = $this->db->prepare("DELETE FROM SPENDING WHERE idSpending = ?");
    	$stmt->execute(array($idSpending));
    }
    
    
}
?>