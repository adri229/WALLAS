<?php
require_once(__DIR__."/../model/Revenue.php");
require_once(__DIR__."/../database/RevenueDAO.php");
require_once(__DIR__."/../rest/BaseRest.php");
require_once(__DIR__ . "/../model/TypeRevenue.php");
require_once(__DIR__ . "/../database/TypeRevenueDAO.php");
require_once(__DIR__ . "/../model/Type.php");
require_once(__DIR__ . "/../database/TypeDAO.php");

/**
 * Clase que recibe las peticiones relacionadas con la gestión de ingresos.
 * Se comunica con otros componentes del servidor para realizar las acciones
 * solicitadas por el cliente y le envía una respuesta acorde al resultado
 * obtenido de la realización de las acciones solicitadas.
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class RevenueRest extends BaseRest
{
	private $revenueDAO;
	private $typeDAO;
    private $typeRevenueDAO;
	
	function __construct()
	{
		parent::__construct();
		$this->revenueDAO = new RevenueDAO();
		$this->typeDAO = new TypeDAO();
		$this->typeRevenueDAO = new TypeRevenueDAO();
	}

	public function create($data)
    {
    	$currentUser = parent::authenticateUser();
    	$revenue = new Revenue();

    	if (isset($data->quantity) && isset($data->name) && isset($data->date)) {
    		$revenue->setDate($data->date);
    		$revenue->setQuantity($data->quantity);
        	$revenue->setName($data->name);
    		$revenue->setOwner($currentUser->getLogin());
    	  	
	    	try {
	    		$idRevenue = $this->revenueDAO->save($revenue);

	    		foreach ($data->types as $type_loop) {
                    $type = $this->typeDAO->findById($type_loop->idType);

                    if ($type == NULL) {
                        $this->revenueDAO->delete($idRevenue);
                        header($this->server->getServerProtocol() . ' 400 Bad request');
                        echo("Type with id " . $type_loop->idType . " not found");
                        return;
                    }
                    $typeRevenue = new TypeRevenue();
                    $typeRevenue->setRevenue($idRevenue);
                    $typeRevenue->setType($type);

                    $this->typeRevenueDAO->save($typeRevenue);
                }
	    		header($this->server->getServerProtocol().' 201 Created');
	      		header('Location: '.$this->server->getRequestUri()."/".$idRevenue);
	      		header('Content-Type: application/json');
	    	} catch (ValidationException $e) {
	    		header($this->server->getServerProtocol().' 400 Bad request');
	      		echo(json_encode($e->getErrors()));
	    	}
	    }
	}

	public function update($idRevenue, $data)
	{
		$currentUser = parent::authenticateUser();

		$revenue = $this->revenueDAO->findById($idRevenue);
		if ($revenue == NULL) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo("Revenue with id ".$idRevenue." not found");
      		return;
    	}

    	if($revenue->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($this->server->getServerProtocol().' 403 Forbidden');
      		echo("you are not the owner of this revenue");
      		return;
    	}

      	if (isset($data->quantity) && isset($data->name) && isset($data->date)) {
      		$revenue->setDate($data->date);
    		$revenue->setQuantity($data->quantity);
        	$revenue->setName($data->name);

        	try {
                $this->typeRevenueDAO->deleteByRevenue($idRevenue);
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
                $typeRevenue = new TypeRevenue();
                $typeRevenue->setRevenue($idRevenue);
                $typeRevenue->setType($type);

                $this->typeRevenueDAO->save($typeRevenue);
            }
    		
	    	try {
	            $this->revenueDAO->update($revenue);
	            header($this->server->getServerProtocol().' 200 Ok');
	        }catch (ValidationException $e) {
	            header($this->server->getServerProtocol().' 400 Bad request');
	            echo(json_encode($e->getErrors()));
	        }
	    }
	}


	public function delete($idRevenue)
	{
		$currentUser = parent::authenticateUser();
		
		$revenue = $this->revenueDAO->findById($idRevenue);
		if ($revenue == NULL) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo("Revenue with id ".$idRevenue." not found");
      		return;
    	}

    	if($revenue->getOwner()->getLogin() != $currentUser->getLogin()) {
    		header($this->server->getServerProtocol().' 403 Forbidden');
      		echo("you are not the owner of this revenue");
      		return;
    	}

    	try {
      		$this->revenueDAO->delete($idRevenue);
      		header($this->server->getServerProtocol().' 200 Ok');
    	}catch (ValidationException $e) {
      		header($this->server->getServerProtocol().' 400 Bad request');
      		echo(json_encode($e->getErrors()));
    	}	
	}

	public function getByOwner($owner, $param)
	{
		$currentUser = parent::authenticateUser();

		$startDate = $this->request->getStartDate();
        $endDate = $this->request->getEndDate();

        $revenues = [];
        $revenues_array = [];

        switch ($param) {
        	case 'crud':
        		$revenues = $this->revenueDAO->findByOwnerAndFilterWithTypes($owner, $startDate, $endDate);
        		if ($revenues == NULL) {
		            header($this->server->getServerProtocol() . ' 400 Bad request');
		            echo("The defined interval time not contains revenues");
		            return;
		        }

		        foreach ($revenues as $revenue) {
		            if ($revenue->getOwner()->getLogin() != $currentUser->getLogin()) {
		                header($this->server->getServerProtocol() . ' 403 Forbidden');
		                echo("you are not the owner of this revenue");
		                return;
		            }
		        }
				
				foreach ($revenues as $revenue) {
					$types_array = [];

                    if( $revenue->getTypes() != NULL) {
                        foreach ($revenue->getTypes() as $type) {
                            array_push($types_array, [
                                "idType" => $type->getIdType(),
                                "name" => $type->getName(),
                                "owner" => $currentUser->getLogin()
                            ]);
                        }
                    }
                    
					array_push($revenues_array, [
						"idRevenue" => $revenue->getIdRevenue(),
						"date" => $revenue->getDate(),
						"quantity" => $revenue->getQuantity(),
		        		"name" => $revenue->getName(),
						"owner" => $revenue->getOwner()->getLogin(),
                        "types" => $types_array
					]);
				}
        		break;
        	case 'chart':
                $revenues = $this->revenueDAO->findByOwnerAndFilter($owner, $startDate, $endDate);

                if ($revenues == NULL) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("The defined interval time not contains revenues");
                    return;
                }

                foreach ($revenues as $revenue) {
                    if ($revenue->getOwner()->getLogin() != $currentUser->getLogin()) {
                        header($this->server->getServerProtocol() . ' 403 Forbidden');
                        echo("you are not the owner of this revenue");
                        return;
                    }
                }

                $revenues_array = [];
                foreach ($revenues as $revenue) {

                    array_push($revenues_array, [
                        "idRevenue" => $revenue->getIdRevenue(),
                        "date" => $revenue->getDate(),
                        "name" => $revenue->getName(),
                        "quantity" => $revenue->getQuantity(),
                        "owner" => $currentUser->getLogin()
                    ]);
                }
                break;
        	default:
        		break;
        }

		
		

		header($this->server->getServerProtocol().' 200 Ok');
    	header('Content-Type: application/json');
    	echo(json_encode($revenues_array));
	}


}

$revenueRest = new RevenueRest();
URIDispatcher::getInstance()
	->map("GET", "/revenues/$1/$2", [$revenueRest, "getByOwner"])	
	->map("POST", "/revenues", [$revenueRest,"create"])
	->map("PUT", "/revenues/$1", [$revenueRest, "update"])
	->map("DELETE", "/revenues/$1", [$revenueRest, "delete"]);

?>
