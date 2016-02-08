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
            array_push($spendings, new Spending($spending["id"],$spending["date"],
                    $spending["quantity"], $spending["owner"]));
        }
        return $spendings;
    }
    
}
?>