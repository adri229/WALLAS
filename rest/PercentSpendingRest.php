<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");

require_once(__DIR__."/../model/Type.php");
require_once(__DIR__."/../database/TypeDAO.php");


require_once(__DIR__."/../rest/BaseRest.php");

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


        /*$typesChart = [];
        foreach ($types as $type) {
            array_push($typesChart, [
                "name" => $type->getName(),
                "percent" => $type->getPercent()
            ]);
        }*/

		header($this->server->getServerProtocol().' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($types));	
  }
}


$percentSpendingRest = new PercentSpendingRest();
URIDispatcher::getInstance()
    ->map("GET", "/percents/$1", array($percentSpendingRest, "getPercents"));

?>
