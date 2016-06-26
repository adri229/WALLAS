<?php

require_once(__DIR__."/../model/Stock.php");
require_once(__DIR__."/../database/StockDAO.php");
require_once(__DIR__."/../model/Spending.php");
require_once(__DIR__."/../database/SpendingDAO.php");
require_once(__DIR__."/../model/Revenue.php");
require_once(__DIR__."/../database/RevenueDAO.php");
require_once(__DIR__."/../rest/BaseRest.php");

/**
 * Clase que genera los porcentajes de tipos de gastos y se los envía al
 * cliente en función del intervalo de fechas proporcionado por el usuario.
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class PositionRest extends BaseRest
{
	private $stockDAO;
	private $spendingDAO;
	private $revenueDAO;
	
	public function __construct()
	{
        parent::__construct();
        $this->stockDAO = new StockDAO();
        $this->spendingDAO = new SpendingDAO();
        $this->revenueDAO = new RevenueDAO();
	}

    /**
     * Metodo que contiene el algoritmo para la generacion de posiciones.
     * Ver diagrama de actividades del Manual Tecnico, pag 67.
     *
     */ 
	public function getPositions($owner)
	{

		$currentUser = parent::authenticateUser();

        $startDate = $this->request->getStartDate();
        $endDate = $this->request->getEndDate();
        
        $stockRef = $this->stockDAO->findByOwnerAndDate($owner, $startDate);
        $stocks = $this->stockDAO->findByOwnerAndFilter($owner, $startDate, $endDate);
        $spendings = $this->spendingDAO->findByOwnerAndFilter($owner, $startDate, $endDate);
        $revenues = $this->revenueDAO->findByOwnerAndFilter($owner, $startDate, $endDate);


        if ($stocks == NULL && $spendings == NULL && $revenues == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("The defined interval time not contains spendings");
            return;
        }
       	
       	$stocksChart = [];
        $stocks_array = [];
 		
     	$begin = new DateTime($startDate);
       	$end = new DateTime($endDate);       			

		$interval = DateInterval::createFromDateString('1 month');
		$period = new DatePeriod($begin, $interval, $end);

		foreach ( $period as $dt ) {
            $aux = new DateTime($dt->format("Y-m-d"));
            $initMonth = $dt;
            $topMonth = $aux->add($interval);

            foreach ($stocks as $stock) {
                if ($stock->getDate() >= $initMonth->format("Y-m-d")  && $stock->getDate() < $topMonth->format("Y-m-d")) {
                    $stockRef = $stock;
                }
            }

            $quantitySpendings = 0;
            foreach ($spendings as $spending) {
                if ($stockRef != NULL) {
                    if ($spending->getDate() >= $stockRef->getDate()  && $spending->getDate() <= $topMonth->format("Y-m-d")) {
                        $quantitySpendings += $spending->getQuantity();
                    }
                } else {
                    if ($spending->getDate() <= $topMonth->format("Y-m-d")) {
                        $quantitySpendings += $spending->getQuantity();
                    }
                }
            }
            
            $quantityRevenues = 0;
            foreach ($revenues as $revenue) {
                if ($stockRef != NULL) {
                    if ($revenue->getDate() >= $stockRef->getDate()  && $revenue->getDate() < $topMonth->format("Y-m-d")) {
                        $quantityRevenues += $revenue->getQuantity();
                    }                        
                } else {
                    if ($revenue->getDate() <= $topMonth->format("Y-m-d")) {
                        $quantityRevenues += $revenue->getQuantity();
                    }
                }    
            }

       		if ($stockRef != NULL) {
       			$total = $stockRef->getTotal() + $quantityRevenues - $quantitySpendings;	
       		} else {
       			$total = $quantityRevenues - $quantitySpendings;
       		}
					
			$stockChart = new Stock();
			$stockChart->setTotal($total);
			$stockChart->setDate($dt->format("Y-m-d"));
            array_push($stocksChart, $stockChart);
         
            $quantitySpendings = 0;
            $quantityRevenues = 0;					
		}

		foreach ($stocksChart as $stock) {
            array_push($stocks_array, [
                "date" => $stock->getDate(),
                "total" => $stock->getTotal(),
            ]);
        }

		header($this->server->getServerProtocol().' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($stocks_array));	
  }
}

$positionsRest = new PositionRest();
URIDispatcher::getInstance()
    ->map("GET", "/positions/$1", array($positionsRest, "getPositions"));
?>
