<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Revenue.php");

/**
 * Clase que gestiona el acceso a la base de datos del modelo Revenue
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class RevenueDAO
{
	private $db;
    public function __construct() 
    {
        $this->db = PDOConnection::getInstance ();
    }
	
    public function findByOwnerAndFilter($owner, $startDate, $endDate)
    {
        $query = "SELECT * FROM REVENUE WHERE owner = ? AND dateRevenue BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($owner, $startDate, $endDate));
        $revenues_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $revenues = array(); 
        foreach ($revenues_db as $revenue) {
            array_push($revenues, new Revenue($revenue["idRevenue"],
                                str_replace(" ", "T", $revenue["dateRevenue"])."Z",
                                $revenue["quantity"], 
                                $revenue["name"], 
                                new User($revenue["owner"])));
        }
        return $revenues;
    }

    public function findByOwnerAndFilterWithTypes($owner, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT r.idRevenue as 'revenue.id',
                r.dateRevenue as 'revenue.date',
                r.quantity as 'revenue.quantity',
                r.name as 'revenue.name',
                r.owner as 'revenue.owner',
                t.idType as 'type.id',
                t.name as 'type.name',
                t.owner as 'type.owner'
            FROM REVENUE r LEFT JOIN TYPE_REVENUE tr  ON r.idRevenue = tr.revenue LEFT JOIN TYPE t on tr.type = t.idType
            WHERE r.owner = ? AND r.dateRevenue BETWEEN ? AND ? ORDER BY r.idRevenue");
        $stmt->execute(array($owner, $startDate, $endDate));
        $revenues_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (sizeof($revenues_db) > 0) {
            $currentRevenue = NULL;
            $revenues = [];
            foreach ($revenues_db as $revenue_loop) {
                if ($currentRevenue == NULL || ($currentRevenue != NULL &&
                    $revenue_loop["revenue.id"] != $currentRevenue->getIdRevenue())) {
                        $currentRevenue = new Revenue($revenue_loop["revenue.id"],
                            str_replace(" ", "T", $revenue_loop["revenue.date"])."Z",
                            $revenue_loop["revenue.quantity"],
                            $revenue_loop["revenue.name"],
                            new User($revenue_loop["revenue.owner"]));
                    array_push($revenues, $currentRevenue);
                }
                if ($revenue_loop["type.id"] != NULL) {
                    $currentRevenue->addType(new Type($revenue_loop['type.id'],
                        $revenue_loop['type.name'],
                        new User($revenue_loop["revenue.owner"])));
                }
            }
            return $revenues;

        } else {
            return NULL;
        }

    }

    public function findById($idRevenue)
    {
    	$stmt = $this->db->prepare("SELECT * FROM REVENUE WHERE idRevenue = ?");
    	$stmt->execute(array($idRevenue));
    	$revenue = $stmt->fetch(PDO::FETCH_ASSOC);
    	
    	if ($revenue != NULL) {
    		return new Revenue(
    			$revenue["idRevenue"],
    			str_replace(" ", "T", $revenue["dateRevenue"])."Z",
    			$revenue["quantity"],
                $revenue["name"],
    			new User($revenue["owner"]));
    	} else {
    		return NULL;
    	}
    }
    
    public function save($revenue)
    {
        $aux = str_replace("T", " ", $revenue->getDate());
        $revenue->setDate(str_replace("Z", "", $aux));
    	$stmt = $this->db->prepare("INSERT INTO REVENUE(dateRevenue,quantity,name,owner) VALUES (?,?,?,?)");
    	$stmt->execute(array($revenue->getDate(), $revenue->getQuantity(), $revenue->getName(),$revenue->getOwner()));
    	return $this->db->lastInsertId();
    }
    
    public function update($revenue)
    {
        $aux = str_replace("T", " ", $revenue->getDate());
        $revenue->setDate(str_replace("Z", "", $aux));
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