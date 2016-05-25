<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");

require_once(__DIR__."/../model/Stock.php");
require_once(__DIR__."/../database/StockDAO.php");

require_once(__DIR__."/../model/Spending.php");
require_once(__DIR__."/../database/SpendingDAO.php");

require_once(__DIR__."/../model/Revenue.php");
require_once(__DIR__."/../database/RevenueDAO.php");


require_once(__DIR__."/../rest/BaseRest.php");

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

	


	public function getPositions($owner)
	{

		$currentUser = parent::authenticateUser();

        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        
        $stockRef = $this->stockDAO->findByOwnerAndDate($owner, $startDate);
        if ($stockRef == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("The defined interval time not contains stocks");
            return;
        }
        $stocks = $this->stockDAO->findByOwnerAndFilter($owner, $startDate, $endDate);
                
        if ($stocks == NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad request');
            echo("The defined interval time not contains stocks");
            return;
        }
	
        $spendings = $this->spendingDAO->findByOwnerAndFilter($owner, $startDate, $endDate);
        $revenues = $this->revenueDAO->findByOwnerAndFilter($owner, $startDate, $endDate);
       	
       	$stocksChart = [];
        $stocks_array = [];
 		
       	
     	$begin = new DateTime($startDate);
       	$end = new DateTime($endDate);       			

		$interval = DateInterval::createFromDateString('1 month');
		$period = new DatePeriod($begin, $interval, $end);


		foreach ( $period as $dt ) {
			//echo $dt->format("Y-m-d\n");
            $aux = new DateTime($dt->format("Y-m-d"));
            $initMonth = $dt;
            $topMonth = $aux->add($interval);
            
            $quantitySpendings = 0;
            foreach ($spendings as $spending) {
                if ($spending->getDate() >= $initMonth->format("Y-m-d")  && $spending->getDate() < $topMonth->format("Y-m-d")) {
                    $quantitySpendings += $spending->getQuantity();
                }
            }

            $quantityRevenues = 0;
            foreach ($revenues as $revenue) {
                if ($revenue->getDate() >= $initMonth->format("Y-m-d")  && $revenue->getDate() < $topMonth->format("Y-m-d")) {
                    $quantityRevenues += $revenue->getQuantity();
                }
            }
       
            foreach ($stocks as $stock) {
            	if ($stock->getDate() >= $initMonth->format("Y-m-d")  && $stock->getDate() < $topMonth->format("Y-m-d")) {
                	$stockRef = $stock;
                }
            }
			$total = $stockRef->getTotal() + $quantityRevenues - $quantitySpendings;
			
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


		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($stocks_array));	
  }
}


$positionsRest = new PositionRest();
URIDispatcher::getInstance()
    ->map("GET", "/positions/$1", array($positionsRest, "getPositions"));

?>
