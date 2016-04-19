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


        

    

     public function findByOwnerWithTypes($owner)
    {
        $stmt = $this->db->prepare("SELECT s.idSpending as 'spending.id',
                s.dateSpending as 'spending.date',
                s.quantity as 'spending.quantity',
                s.name as 'spending.name',
                s.owner as 'spending.owner',
                t.idType as 'type.id',
                t.name as 'type.name',
                t.owner as 'type.owner'
            FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType 
            WHERE s.owner = ? ");
        $stmt->execute(array($owner));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
    


        if (sizeof($spendings_db) > 0) {
            $currentSpending = NULL;
            $spendings = [];
            foreach ($spendings_db as $spending_loop) {
                if ($currentSpending == NULL || ($currentSpending != NULL && 
                    $spending_loop["spending.id"] != $currentSpending->getIdSpending())) {
                        $currentSpending = new Spending($spending_loop["spending.id"],
                            $spending_loop["spending.date"],
                            $spending_loop["spending.quantity"],
                            $spending_loop["spending.name"],
                            new User($spending_loop["spending.owner"]));
                    array_push($spendings, $currentSpending);
                }
                if ($spending_loop["type.id"] != NULL) {
                    $currentSpending->addType(new Type($spending_loop['type.id'],
                        $spending_loop['type.name'],
                        new User($spending_loop["spending.owner"])));
                }   
            }

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