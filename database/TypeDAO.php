<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Type.php");

class TypeDAO
{
	private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }

    public function findByOwner($owner)
    {
        $stmt = $this->db->prepare("SELECT * FROM TYPE WHERE owner = ?");
        $stmt->execute(array($owner));
        $types_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $types = array();
        
        foreach ($types_db as $type) {
            array_push($types, new Type($type["idType"], $type["name"], new User($type["owner"])));
        }
        return $types;
    }

    public function findById($idType)
    {
        $stmt = $this->db->prepare("SELECT * FROM TYPE WHERE idType = ?");
        $stmt->execute(array($idType));
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($type != NULL) {
            return new Type(
                $type["idType"],
                $type["name"],
                new User($type["owner"]));
        } else {
            return NULL;
        }
    }

	public function save($type)
    {
    	$stmt = $this->db->prepare("INSERT INTO TYPE(name,owner) VALUES (?,?)");
    	$stmt->execute(array($type->getName(), $type->getOwner()));
    	return $this->db->lastInsertId();
    }
    
    public function update($type)
    {
    	$stmt = $this->db->prepare("UPDATE TYPE SET name = ?, owner = ? WHERE idType = ?");
    	$stmt->execute(array($type->getName(), $type->getOwner()->getLogin(), $type->getIdType()));    	
    }
    
    public function delete($idType)
    {
    	$stmt = $this->db->prepare("DELETE FROM TYPE WHERE idType = ?");
    	$stmt->execute(array($idType));
    }




}
?>
