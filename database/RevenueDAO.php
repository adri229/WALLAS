<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Revenue.php");

class RevenueDAO
{
	private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }
	
    public function findByOwner($owner)
    {
        $stmt = $this->db->prepare("SELECT * FROM REVENUE WHERE owner = ?");
        $stmt->execute(array($owner));
        $revenues_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $revenues = array();
        
        foreach ($revenues_db as $revenue) {
            array_push($revenues, new Revenue($revenue["idRevenue"],$revenue["dateRevenue"],
                    $revenue["quantity"], $revenue["name"], new User($revenue["owner"])));
        }
        return $revenues;
    }

    public function findByOwnerAndFilter($owner, $startDate, $endDate)
    {
        $query = "SELECT * FROM REVENUE WHERE owner = ? AND dateRevenue BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($owner, $startDate, $endDate));
        $revenues_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $revenues = array();
        
        foreach ($revenues_db as $revenue) {
            array_push($revenues, new Revenue($revenue["idRevenue"],$revenue["dateRevenue"],
                    $revenue["quantity"], $revenue["name"], new User($revenue["owner"])));
        }
        return $revenues;
    }


    
    public function findById($idRevenue)
    {
    	$stmt = $this->db->prepare("SELECT * FROM REVENUE WHERE idRevenue = ?");
    	$stmt->execute(array($idRevenue));
    	$revenue = $stmt->fetch(PDO::FETCH_ASSOC);
    	
    	if ($revenue != NULL) {
    		return new Revenue(
    			$revenue["idRevenue"],
    			$revenue["dateRevenue"],
    			$revenue["quantity"],
                $revenue["name"],
    			new User($revenue["owner"]));
    	} else {
    		return NULL;
    	}
    }
    
    public function save($revenue)
    {
    	$stmt = $this->db->prepare("INSERT INTO REVENUE(dateRevenue,quantity,name,owner) VALUES (?,?,?,?)");
    	$stmt->execute(array($revenue->getDate(), $revenue->getQuantity(), $revenue->getName(),$revenue->getOwner()));
    	return $this->db->lastInsertId();
    }
    
    public function update($revenue)
    {
    	$stmt = $this->db->prepare("UPDATE REVENUE SET dateRevenue = ?, quantity = ?, 
            name = ?, owner = ? WHERE idRevenue = ?");
    	$stmt->execute(array($revenue->getDate(),$revenue->getQuantity(), $revenue->getName(), 
            $revenue->getOwner()->getLogin(), $revenue->getIdRevenue()));    	
    }
    
    public function delete($idRevenue)
    {
    	$stmt = $this->db->prepare("DELETE FROM REVENUE WHERE idRevenue = ?");
    	$stmt->execute(array($idRevenue));
    }
    



}
?>
