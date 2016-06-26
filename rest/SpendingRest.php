<?php

require_once(__DIR__ . "/../model/Spending.php");
require_once(__DIR__ . "/../database/SpendingDAO.php");
require_once(__DIR__ . "/../model/TypeSpending.php");
require_once(__DIR__ . "/../database/TypeSpendingDAO.php");
require_once(__DIR__ . "/../model/Type.php");
require_once(__DIR__ . "/../database/TypeDAO.php");
require_once(__DIR__ . "/../rest/BaseRest.php");

/**
 * Clase que recibe las peticiones relacionadas con la gestión de gastos. Se
 * comunica con otros componentes del servidor para realizar las acciones
 * solicitadas por el cliente y le envía una respuesta acorde al resultado
 * obtenido de la realización de las acciones solicitadas.
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

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

        if (isset($data->quantity) && isset($data->name) && isset($data->date)) {
            $spending->setDate($data->date);
            $spending->setQuantity($data->quantity);
            $spending->setName($data->name);
            $spending->setOwner($currentUser->getLogin());


            try {
                $idSpending = $this->spendingDAO->save($spending);

                foreach ($data->types as $type_loop) {
                    $type = $this->typeDAO->findById($type_loop->idType);

                    if ($type == NULL) {
                        $this->spendingDAO->delete($idSpending);
                        header($this->server->getServerProtocol() . ' 400 Bad request');
                        echo("Type with id " . $type_loop->idType . " not found");
                        return;
                    }
                    $typeSpending = new TypeSpending();
                    $typeSpending->setSpending($idSpending);
                    $typeSpending->setType($type);

                    $this->typeSpendingDAO->save($typeSpending);
                }
                header($this->server->getServerProtocol() . ' 201 Created');
                header('Location: ' . $this->server->getRequestUri() . "/" . $idSpending);
                header('Content-Type: application/json');
            } catch (ValidationException $e) {
                header($this->server->getServerProtocol() . ' 400 Bad request');
                echo(json_encode($e->getErrors()));
            }
        }
    }

    public function update($idSpending, $data) {

        $currentUser = parent::authenticateUser();

        $spending = $this->spendingDAO->findById($idSpending);
        if ($spending == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }

        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($this->server->getServerProtocol() . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        if (isset($data->quantity) && isset($data->name) && isset($data->date)) {
            $spending->setDate($data->date);
            $spending->setQuantity($data->quantity);
            $spending->setName($data->name);

            try {
                $this->typeSpendingDAO->deleteBySpending($idSpending);
                header($this->server->getServerProtocol() . ' 200 Ok');
            } catch (ValidationException $e) {
                header($this->server->getServerProtocol() . ' 400 Bad request');
                echo(json_encode($e->getErrors()));
            }
            foreach ($data->types as $type_loop) {
                $type = $this->typeDAO->findById($type_loop->idType);

                if ($type == NULL) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("Type with id " . $type_loop->idType . " not found");
                    return;
                }
                $typeSpending = new TypeSpending();
                $typeSpending->setSpending($idSpending);
                $typeSpending->setType($type);

                $this->typeSpendingDAO->save($typeSpending);
            }

            try {
                $this->spendingDAO->update($spending);
                header($this->server->getServerProtocol() . ' 200 Ok');
            } catch (ValidationException $e) {
                header($this->server->getServerProtocol() . ' 400 Bad request');
                echo(json_encode($e->getErrors()));
            }
        }
    }

    public function delete($idSpending) {
        $currentUser = parent::authenticateUser();

        $spending = $this->spendingDAO->findById($idSpending);
        if ($spending == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("Spending with id " . $idSpending . " not found");
            return;
        }

        if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
            header($this->server->getServerProtocol() . ' 403 Forbidden');
            echo("you are not the owner of this spending");
            return;
        }

        try {
            $this->spendingDAO->delete($idSpending);
            header($this->server->getServerProtocol() . ' 200 Ok');
        } catch (ValidationException $e) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo(json_encode($e->getErrors()));
        }
    }

    public function getByOwner($owner, $param) {
        $currentUser = parent::authenticateUser();

        $startDate = $this->request->getStartDate();
        $endDate = $this->request->getEndDate();

        $spendings = [];
        $spendings_array = [];

        switch ($param) {
            case 'crud':
                $spendings = $this->spendingDAO->findByOwnerAndFilterWithTypes($owner, $startDate, $endDate);

                if ($spendings == NULL) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("The defined interval time not contains spendings");
                    return;
                }

                foreach ($spendings as $spending) {
                    if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
                        header($this->server->getServerProtocol() . ' 403 Forbidden');
                        echo("you are not the owner of this spending");
                        return;
                    }
                }

                $spendings_array = [];
                foreach ($spendings as $spending) {
                    $types_array = [];

                    if( $spending->getTypes() != NULL) {
                        foreach ($spending->getTypes() as $type) {
                            array_push($types_array, [
                                "idType" => $type->getIdType(),
                                "name" => $type->getName(),
                                "owner" => $currentUser->getLogin()
                            ]);
                        }
                    }

                    array_push($spendings_array, [
                        "idSpending" => $spending->getIdSpending(),
                        "date" => $spending->getDate(),
                        "name" => $spending->getName(),
                        "quantity" => $spending->getQuantity(),
                        "owner" => $currentUser->getLogin(),
                        "types" => $types_array
                    ]);

                }
                break;
            case 'chart':
                $spendings = $this->spendingDAO->findByOwnerAndFilter($owner, $startDate, $endDate);

                if ($spendings == NULL) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("The defined interval time not contains spendings");
                    return;
                }

                foreach ($spendings as $spending) {
                    if ($spending->getOwner()->getLogin() != $currentUser->getLogin()) {
                        header($this->server->getServerProtocol() . ' 403 Forbidden');
                        echo("you are not the owner of this spending");
                        return;
                    }
                }

                $spendings_array = [];
                foreach ($spendings as $spending) {

                    array_push($spendings_array, [
                        "idSpending" => $spending->getIdSpending(),
                        "date" => $spending->getDate(),
                        "name" => $spending->getName(),
                        "quantity" => $spending->getQuantity(),
                        "owner" => $currentUser->getLogin()
                    ]);
                }
                break;
            default:
                break;
        }
        header($this->server->getServerProtocol() . ' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($spendings_array));
    }
}

$spendingRest = new SpendingRest();
URIDispatcher::getInstance()
        ->map("GET", "/spendings/$1/$2", [$spendingRest, "getByOwner"])
        ->map("POST", "/spendings", [$spendingRest, "create"])
        ->map("PUT", "/spendings/$1/", [$spendingRest, "update"])
        ->map("DELETE", "/spendings/$1", [$spendingRest, "delete"]);
?>
