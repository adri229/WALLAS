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
            return new Spending($spending["idSpending"],str_replace(" ", "T", $spending["dateSpending"])."Z",
                    $spending["quantity"], $spending["name"], new User($spending["owner"]));
        } else {
            return NULL;
        }
    }


     public function findByOwnerAndFilterWithTypes($owner, $startDate, $endDate)
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
            WHERE s.owner = ? AND s.dateSpending BETWEEN ? AND ? ORDER BY s.idSpending");
        $stmt->execute(array($owner, $startDate, $endDate));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);



        if (sizeof($spendings_db) > 0) {
            $currentSpending = NULL;
            $spendings = [];
            foreach ($spendings_db as $spending_loop) {
                if ($currentSpending == NULL || ($currentSpending != NULL &&
                    $spending_loop["spending.id"] != $currentSpending->getIdSpending())) {
                        $currentSpending = new Spending($spending_loop["spending.id"],
                            str_replace(" ", "T", $spending_loop["spending.date"])."Z",
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


    public function findByOwnerAndFilter($owner, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT * FROM SPENDING WHERE owner = ? AND dateSpending BETWEEN ? AND ?");
        $stmt->execute(array($owner, $startDate, $endDate));
        $spendings_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $spendings = array();

        foreach ($spendings_db as $spending) {
            array_push($spendings, new Spending($spending["idSpending"],str_replace(" ", "T", $spending["dateSpending"]."Z"),
                $spending["quantity"], $spending["name"], new User($spending["owner"])));
        }
        return $spendings;
    }


    public function save($spending)
    {
        $aux = str_replace("T", " ", $spending->getDate());
        $spending->setDate(str_replace("Z", "", $aux));
    	$stmt = $this->db->prepare("INSERT INTO SPENDING(dateSpending,quantity,name,owner) VALUES (?,?,?,?)");
    	$stmt->execute(array($spending->getDate(), $spending->getQuantity(), $spending->getName(),$spending->getOwner()));
    	return $this->db->lastInsertId();
    }

    public function update($spending)
    {
        $aux = str_replace("T", " ", $spending->getDate());
        $spending->setDate(str_replace("Z", "", $aux));
    	$stmt = $this->db->prepare("UPDATE SPENDING SET dateSpending = ?, quantity = ?, name = ?,owner = ? WHERE idSpending = ?");
    	$stmt->execute(array($spending->getDate(), $spending->getQuantity(), $spending->getName(),
            $spending->getOwner()->getLogin(), $spending->getIdSpending()));
    }

    public function delete($idSpending)
    {
    	$stmt = $this->db->prepare("DELETE FROM SPENDING WHERE idSpending = ?");
    	$stmt->execute(array($idSpending));
    }


}
?>
