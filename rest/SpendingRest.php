<?php

require_once(__DIR__ . "/../model/User.php");
require_once(__DIR__ . "/../database/UserDAO.php");

require_once(__DIR__ . "/../model/Spending.php");
require_once(__DIR__ . "/../database/SpendingDAO.php");

require_once(__DIR__ . "/../model/TypeSpending.php");
require_once(__DIR__ . "/../database/TypeSpendingDAO.php");

require_once(__DIR__ . "/../model/Type.php");
require_once(__DIR__ . "/../database/TypeDAO.php");

require_once(__DIR__ . "/../rest/BaseRest.php");

class SpendingRest extends BaseRest {

    private $spendingDAO;
    private $typeDAO;
    private $typeSpendingDAO;

    public function __construct() {
        parent::__construct();
        $this->spendingDAO = new SpendingDAO();
        $this->typeDAO = new TypeDAO();
        $this->typeSpendingDAO = new TypeSpendingDAO();
    }

    public function create($data) {
        $currentUser = parent::authenticateUser();
        $spending = new Spending();

        if (isset($data->quantity)) {
            $date = new DateTime();
            $spending->setDateSpending($date->getTimestamp());
            $spending->setQuantity($data->quantity);
            $spending->setOwner($currentUser->getLogin());


            try {
                //$spending->validate();	
                $idSpending = $this->spendingDAO->save($spending);
                header($_SERVER['SERVER_PROTOCOL'] . ' 201 Created');
                header('Location: ' . $_SERVER['REQUEST_URI'] . "/" . $idSpending);
                header('Content-Type: application/json');
            } catch (ValidationException $e) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
                echo(json_encode($e->getErrors()));
            }
        }
    }

    public function updateQuantity($idSpending, $data) {
        $currentUser = parent::authenticateUser();

        $spending = $this->spendingDAO->findById($idSpending);
        if ($spending == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }


        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        if (isset($data->quantity)) {
            $date = new DateTime();
            $spending->setDateSpending($date->getTimestamp());
            $spending->setQuantity($data->quantity);

            try {
                // validate Post object
                //$spending->validate(); // if it fails, ValidationException
                $this->spendingDAO->update($spending);
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok');
            } catch (ValidationException $e) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
                echo(json_encode($e->getErrors()));
            }
        }
    }

    public function updateTypes($idSpending, $types) {
        $currentUser = parent::authenticateUser();


        $spending = $this->spendingDAO->findById($idSpending);
        if ($spending == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }

        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        try {
            $this->typeSpendingDAO->deleteBySpending($idSpending);
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok');
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo(json_encode($e->getErrors()));
        }

        if (isset($types)) {

            $arrayType = [];
            foreach ($types->types as $idType) {
                $type = $this->typeDAO->findById($idType);
                

                if ($type == NULL) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
                    echo("Type with id " . $idType . " not found");
                    return;
                }
                $typeSpending = new TypeSpending();
                $typeSpending->setSpending($idSpending);
                $typeSpending->setType($type);

           

                $this->typeSpendingDAO->save($typeSpending);
            }
        }
    }

    public function delete($idSpending) {
        $currentUser = parent::authenticateUser();

        $spending = $this->spendingDAO->findById($idSpending);
        if ($spending == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }


        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        try {        	
            $this->typeSpendingDAO->deleteBySpending($idSpending);
            $this->spendingDAO->delete($idSpending);
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok');
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo(json_encode($e->getErrors()));
        }
    }

    public function getByOwner($owner) {
        $currentUser = parent::authenticateUser();

        $startDate = $_GET["startDate"];
        $endDate = $_GET["endDate"];

        $spendings = $this->spendingDAO->findByOwnerWithTypes($owner);

        if ($spendings == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("The defined interval time not contains spendings");
            return;
        }

        foreach ($spendings as $spending) {
            if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
                echo("you are not the owner of this spending");
                return;
            }
        }



        $spendings_array = [];
        foreach ($spendings as $spending) {
        	$types_array = [];
        	
            foreach ($spending->getType() as $type) {
	            array_push($types_array, [
	                "idType" => $type->getIdType(),
	                "date" => $type->getDateType(),
	                "name" => $type->getName(),
	                "owner" => $type->getOwner()
	            ]);	
            }
            
            
            array_push($spendings_array, [
                "idSpending" => $spending->getIdSpending(),
                "dateSpending" => $spending->getDateSpending(),
                "quantity" => $spending->getQuantity(),
                "owner" => $spending->getOwner()->getLogin(),
                "types" => $types_array
            ]);
            
        }

       
        
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($spendings_array));
    }

    /*
    public function getById($idSpending) {
        $currentUser = parent::authenticateUser();

        $spending = $this->spendingDAO->findById($spending);

        if ($spending == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }


        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        $spending_array = array();

        array_push($spending_array, array(
            "idSpending" => $spending->getIdSpending(),
            "dateSpending" => $spending->getDateSpending(),
            "quantity" => $spending->getQuantity(),
            "owner" => $spending->getOwner()->getLogin()
        ));

        $spending_array["types"] = array();
        foreach ($spending->getType() as $type) {
            array_push($spending_array["types"], array(
                "idType" => $type->getIdType(),
                "name" => $type->getName(),
                "owner" => $type->getOwner()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($spending_array));
    }
*/
}

$spendingRest = new SpendingRest();
URIDispatcher::getInstance()
        ->map("GET", "/spendings/$1", [$spendingRest, "getByOwner"])
        ->map("POST", "/spendings", [$spendingRest, "create"])
        ->map("PUT", "/spendings/$1", [$spendingRest, "updateQuantity"])
        ->map("PUT", "/spendings/$1/types", [$spendingRest, "updateTypes"])
        ->map("DELETE", "/spendings/$1", [$spendingRest, "delete"]);
?>