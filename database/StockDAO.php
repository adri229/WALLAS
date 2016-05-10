<?php

require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/Stock.php");
require_once(__DIR__."/../model/User.php");

class StockDAO
{
    private $db;
    public function __construct() {
        $this->db = PDOConnection::getInstance ();
    }

    public function findByOwner($owner)
    {
        $stmt = $this->db->prepare("SELECT * FROM STOCK WHERE owner = ?");
        $stmt->execute(array($owner));
        $stocks_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stocks = array();

        foreach ($stocks_db as $stock) {
            array_push($stocks, new Stock($stock["idStock"],$stock["dateStock"],
                    $stock["total"], new User($stock["owner"])));
        }
        return $stocks;
    }

    public function findByOwnerAndFilter($owner, $startDate, $endDate)
    {
        $query = "SELECT * FROM STOCK WHERE owner = ? AND dateStock BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array($owner, $startDate, $endDate));
        $stocks_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stocks = array();

        foreach ($stocks_db as $stock) {
            array_push($stocks, new Stock($stock["idStock"],str_replace(" ","T", $stock["dateStock"])."Z",
                    $stock["total"], new User($stock["owner"])));
        }
        return $stocks;
    }



    public function findById($idStock)
    {
        $stmt = $this->db->prepare("SELECT * FROM STOCK WHERE idStock = ?");
        $stmt->execute(array($idStock));
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stock != NULL) {
            return new Stock(
                $stock["idStock"],
                str_replace(" ","T", $stock["dateStock"])."Z",
                $stock["total"],
                new User($stock["owner"]));
        } else {
            return NULL;
        }
    }

    public function save($stock)
    {
        $stmt = $this->db->prepare("INSERT INTO STOCK(dateStock,total,owner) VALUES (?,?,?)");
        $stmt->execute(array($stock->getDate(), $stock->getTotal(), $stock->getOwner()));
        return $this->db->lastInsertId();
    }

    public function update($stock)
    {
        $stmt = $this->db->prepare("UPDATE STOCK SET dateStock = ?, total = ?, owner = ? WHERE idStock = ?");
        $stmt->execute(array($stock->getDate(), $stock->getTotal(), $stock->getOwner()->getLogin(), $stock->getIdStock()));
    }

    public function delete($idStock)
    {
        $stmt = $this->db->prepare("DELETE FROM STOCK WHERE idStock = ?");
        $stmt->execute(array($idStock));
    }



}
?>