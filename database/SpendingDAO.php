<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Spending.php");


class SpendingDAO
{
    private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }
	
    public function findAllByUser($owner)
    {
        $stmt = $this->db->prepare("SELECT * FROM USER SPENDING owner = ?");
        $stmt->execute(array($owner));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $spendings = array();
        
        foreach ($spendings_db as $spending) {
            array_push($spendings, new Spending($spending["idSpending"],$spending["dateSpending"],
                    $spending["quantity"], $spending["owner"]));
        }
        return $spendings;
    }
    
    public function findById($idSpending)
    {
    	$stmt = $this->db->prepare("SELECT * FROM SPENDING WHERE idSpending = ?");
    	$stmt->execute(array($idSpending));
    	$spending = $stmt->fetch(PDO::FETCH_ASSOC);
    	
    	if ($spending != NULL) {
    		return new Spending(
    			$spending["idSpending"],
    			$spending["dateSpending"],
    			$spending["quantity"],
    			new User($spending["owner"]));
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