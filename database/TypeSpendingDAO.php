<?php

/**
 * Clase que gestiona el acceso a la base de datos del modelo TypeSpending
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class TypeSpendingDAO
{
	private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }

    public function save($typeSpending)
    {
    	$stmt = $this->db->prepare("INSERT INTO TYPE_SPENDING(type,spending) VALUES (?,?)");
    	$stmt->execute(array($typeSpending->getType()->getIdType(), $typeSpending->getSpending()));
    }
    
    public function update($typeSpending)
    {
    	$stmt = $this->db->prepare("UPDATE TYPE_SPENDING SET type = ?, spending = ? WHERE idTypeSpending = ?");
    	$stmt->execute(array($typeSpending->getType(), $typeSpending->getSpending(), $typeSpending->getIdTypeSpending));    	
    }
    
    public function delete($idTypeSpending)
    {
    	$stmt = $this->db->prepare("DELETE FROM TYPE_SPENDING WHERE idTypeSpending = ?");
    	$stmt->execute(array($idTypeSpending));
    }

    public function deleteBySpending($idSpending)
    {
        $stmt = $this->db->prepare("DELETE FROM TYPE_SPENDING WHERE spending = ?");
        $stmt->execute(array($idSpending));
    }

    public function deleteByType($idType)
    {
        $stmt = $this->db->prepare("DELETE FROM TYPE_SPENDING WHERE type = ?");
        $stmt->execute(array($idType));
    }
}
?>