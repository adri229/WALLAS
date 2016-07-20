<?php

/**
 * Clase que gestiona el acceso a la base de datos del modelo TypeRevenue
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class TypeRevenueDAO
{
	private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    public function save($typeRevenue)
    {
    	$stmt = $this->db->prepare("INSERT INTO TYPE_REVENUE(type,revenue) VALUES (?,?)");
    	$stmt->execute(array($typeRevenue->getType()->getIdType(), $typeRevenue->getRevenue()));
    }
    
    public function update($typeRevenue)
    {
    	$stmt = $this->db->prepare("UPDATE TYPE_REVENUE SET type = ?, revenue = ? WHERE idTypeRevenue = ?");
    	$stmt->execute(array($typeRevenue->getType(), $typeRevenue->getRevenue(), $typeRevenue->getIdTypeSpending));    	
    }
    
    public function delete($idTypeRevenue)
    {
    	$stmt = $this->db->prepare("DELETE FROM TYPE_REVENUE WHERE idTypeRevenue = ?");
    	$stmt->execute(array($idTypeRevenue));
    }

    public function deleteByRevenue($idRevenue)
    {
        $stmt = $this->db->prepare("DELETE FROM TYPE_REVENUE WHERE revenue = ?");
        $stmt->execute(array($idRevenue));
    }

    public function deleteByType($idType)
    {
        $stmt = $this->db->prepare("DELETE FROM TYPE_REVENUE WHERE type = ?");
        $stmt->execute(array($idType));
    }
}
?>