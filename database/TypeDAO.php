<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Type.php");

/**
 * Clase que gestiona el acceso a la base de datos del modelo Type
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class TypeDAO
{
	private $db;
    public function __construct() 
    {
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

    /**
     * Metodo que calcula el porcentaje de los tipos de gasto del usuario pasado
     * como parametro en funcion del rango de fechas pasado como parametro.
     * Para realizar el calculo, primero obtiene todos los gastos y la cantidad
     * numerica de gasto que representan. Despues se invoca a un metodo privado
     * para conocer el total de gasto del usuario y se calculan los porcentajes
     * de cada tipo de gasto. Finalmente, se crea un tipo de gasto especial que
     * representa el porcentaje de gasto de aquellos gastos que no tengan ningun
     * tipo de gasto asignado.
     */
    public function findByOwnerAndFilterWithPercents($owner, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT SUM(s.quantity) as 'spending.quantity',
                t.idType as 'type.id',
                t.name as 'type.name'
        FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType
        WHERE s.owner = ?  AND s.dateSpending BETWEEN ? AND ? AND ts.spending IS NOT NULL GROUP BY ts.type");

        $stmt->execute(array($owner, $startDate, $endDate));
        $types_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (sizeof($types_db)) {
            $total = $this->getTotal($owner, $startDate, $endDate);

            $types = [];
            foreach ($types_db as $type_loop) {
                $type = new Type();
                $type->setName($type_loop['type.name']);
                $percent = ($type_loop["spending.quantity"] * 100) / $total;

                array_push($types, array("typename"=>$type->getName(), "percent"=>round($percent,2), "total"=>(float)$type_loop["spending.quantity"]));
            }

            $totalWithoutType = $this->getTotalSpendingsWithoutType($owner, $startDate, $endDate);
            if ($totalWithoutType != 0) {
            	$percentSpecialType = ($totalWithoutType * 100) / $total;
	            $specialType = new Type();
	            $specialType->setName("Without category");
	            array_push($types, array("typename"=>$specialType->getName(), "percent"=>round($percentSpecialType,2), "total"=>(float)$totalWithoutType));
            }
            return $types;
        }
        else {
            return NULL;
        }
    }

    private function getTotal($owner, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT SUM(s.quantity) as 'spending.quantity' FROM SPENDING s
            WHERE s.owner = ? AND s.dateSpending BETWEEN ? AND ?");

        $stmt->execute(array($owner, $startDate, $endDate));
        $total = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ($total[0]['spending.quantity']);
    }

    private function getTotalSpendingsWithoutType($owner, $startDate, $endDate)
    {
    	$stmt = $this->db->prepare("SELECT SUM(s.quantity) as 'spending.quantity' FROM SPENDING s
    	LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType
            WHERE s.owner = ? AND s.dateSpending BETWEEN ? AND ? AND ts.type IS NULL");

        $stmt->execute(array($owner, $startDate, $endDate));
        $total = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ($total[0]['spending.quantity']);
    }

    public function isNewType($type) {
        $stmt = $this->db->prepare("SELECT COUNT(name) FROM TYPE where name = ?");
        $stmt->execute(array($type));

        if ($stmt->fetchColumn() == 0) {
            return true;
        }
    }
}
?>
