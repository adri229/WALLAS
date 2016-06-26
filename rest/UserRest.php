<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");
require_once(__DIR__."/BaseRest.php");
require_once(__DIR__."/../components/ServerWrapper.php");

/**
 * Clase que recibe las peticiones relacionadas con la gestión de usuarios. Se 
 * comunica con otros componentes del servidor para realizar las acciones
 * solicitadas por el cliente y le envía una respuesta acorde al resultado
 * obtenido de la realización de las acciones solicitadas.
 * 
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class UserRest extends BaseRest 
{
    private $userDAO;
    
    public function __construct() {
        parent::__construct();
        
        $this->userDAO = new \UserDAO();
    }
    
    public function create($data)
    {
    	$required = 
		    isset($data->login)	     &&
		    isset($data->passwd)	 &&
    		isset($data->verifyPass) &&
	    	isset($data->fullname)	 &&
		    isset($data->email)	     &&
		    isset($data->phone)	     &&
	    	isset($data->country);

    	if (!$required || $data->passwd != $data->verifyPass) {
    		header($this->server->getServerProtocol() .' 400 Bad request');
    		echo("The entered passwords do not match");
    		return;
    	}

        if (!$this->userDAO->isNewLogin($data->login)) {
	       	header($this->server->getServerProtocol() .' 400 Bad request');
	    	echo("This user already exits");
		    return;
        }

        $user = new \User(strtolower($data->login), password_hash($data->passwd,PASSWORD_BCRYPT), $data->fullname, 
                $data->email, str_replace(" ", "", $data->phone), $data->country);
        
        
        try {
            $this->userDAO->save($user);
            header($this->server->getServerProtocol() .' 201 Created');
            header("Location: ". $this->server->getRequestUri() ."/".$data->login);
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        } 
    }
    
    

    public function update($login, $attribute, $data)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->userDAO->findByID($login);

        if ($user == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo("User with id ".$login." not found");
            return;
        }

        switch ($attribute) {
            case 'account':
                $required = 
                    isset($data->fullname)   &&
                    isset($data->email)      &&
                    isset($data->phone)      &&
                    isset($data->country);
                
                if (!$required) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("The entered data is not valid");
                    return;
                }
                $user->setFullname($data->fullname);
                $user->setEmail($data->email);
                $user->setPhone(str_replace(" ", "", $data->phone));
                $user->setCountry($data->country);

                break;
            case 'password':

                if ($data->passwd != $data->verifyPass) {
                    header($this->server->getServerProtocol() . ' 400 Bad request');
                    echo("The entered passwords do not match");
                    return;
                }

                $user->setPassword(password_hash($data->passwd,PASSWORD_BCRYPT));
                
                break;
            default:
                break;
        }

        try {
            $this->userDAO->update($user);
            header($this->server->getServerProtocol() . ' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }  

    }

    
    public function delete ($login) 
    {
    	$currentUser = parent::authenticateUser();
    	if ($login != $currentUser->getLogin()) {
    		header($this->server->getServerProtocol() . ' 403 Forbidden');
    		echo("You are not authorized to access this resource");
    		return;
    	}

    	try {
    		$this->userDAO->delete($login);
    		header($this->server->getServerProtocol() . ' 200 Ok');
    	} catch (Exception $e) {
    		http_response_code(400);
    		echo(json_encode($e->getErrors()));
    	}
    }
    
    public function get($login)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($this->server->getServerProtocol() . ' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        } 

	    $user = $this->userDAO->findByID($login);
        if ($user == NULL) {
            header($this->server->getServerProtocol() . ' 400 Bad request');
            echo ("User with login ".$login." not found");
            return;
        }
        
        $user_array = array(
            "login" => $user->getLogin(),
            "password" => $user->getPassword(),
            "fullname" => $user->getFullname(),
            "email" => $user->getEmail(),
            "phone" => $user->getPhone(),
            "country" => $user->getCountry()
        );
        
        header($this->server->getServerProtocol() . ' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($user_array));        
    }

    public function login($login)
    {
        $currentLogged = parent::authenticateUser();
        if($currentLogged->getLogin() != $login) {
            header($this->server->getServerProtocol() . ' 403 Forbidden');
            echo("You are not authorized to login as anyone but you");
        } else {
            header($this->server->getServerProtocol() . ' 200 Ok');
            echo("Hello ".$login);
        }       
    }
    
    
	
}

$userRest = new UserRest();
\URIDispatcher::getInstance()
    ->map("GET", "/users/$1", array($userRest, "get"))
    ->map("POST", "/users/login/$1", array($userRest, "login"))
    ->map("POST", "/users", array($userRest, "create"))
    ->map("PUT", "/users/$1/$2", array($userRest, "update"))
    ->map("DELETE", "/users/$1", array($userRest, "delete"));
	
?>
