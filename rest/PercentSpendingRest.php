<?php

require_once(__DIR__."/../model/Type.php");
require_once(__DIR__."/../database/TypeDAO.php");
require_once(__DIR__."/../rest/BaseRest.php");

/**
 * Clase que genera los porcentajes de tipos de gastos y se los envía al
 * cliente en función del intervalo de fechas proporcionado por el usuario.
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class PercentSpendingRest extends BaseRest
{
	private $typeDAO;

	public function __construct()
	{
        parent::__construct();
        $this->typeDAO = new TypeDAO();
	}

	public function getPercents($owner)
	{
        $currentUser = parent::authenticateUser();

        $startDate = $this->request->getStartDate();
        $endDate = $this->request->getEndDate();

        $types = $this->typeDAO->findByOwnerAndFilterWithPercents($owner, $startDate, $endDate);
		if ($types == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("The defined interval time not contains percents");
            return;
        }

		header($this->server->getServerProtocol().' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($types));	
  }
}


$percentSpendingRest = new PercentSpendingRest();
URIDispatcher::getInstance()
    ->map("GET", "/percents/$1", array($percentSpendingRest, "getPercents"));
?>
