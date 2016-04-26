<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");
require_once(__DIR__."/BaseRest.php");
/**
 * Refactorizar cuando se cree la clase Server
 * @author acfernandez4
 *
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
    		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
    		echo("The entered passwords do not match");
    		return;
    	}

        if (!$this->userDAO->isNewLogin($data->login)) {
	       	header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
	    	echo("This user already exits");
		    return;
        }



        $user = new \User(strtolower($data->login), $data->passwd, $data->fullname, 
                $data->email, str_replace(" ", "", $data->phone), $data->country);
        
        
        try {
            //$user->validate();
            $this->userDAO->save($user);
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        } 
    }
    
    

    public function update($login, $data)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->userDAO->findByID($login);

        if ($user == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("User with id ".$login." not found");
            return;
        }

        $required = 
            isset($data->passwd)     &&
            isset($data->verifyPass) &&
            isset($data->email)      &&
            isset($data->phone)      &&
            isset($data->country);

        if (!$required || $data->passwd != $data->verifyPass) {
            print_r($data->passwd);
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("The entered passwords do not match");
            return;
        }

        $user->setPassword($data->passwd);
        $user->setEmail($data->email);
        $user->setPhone($data->phone);
        $user->setCountry($data->country);


        try {
            //$user->validate();
            $this->userDAO->update($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }  

    }

    
    public function delete ($login) 
    {
    	$currentUser = parent::authenticateUser();
    	if ($login != $currentUser->getLogin()) {
    		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
    		echo("You are not authorized to access this resource");
    		return;
    	}

    	try {
    		$this->userDAO->delete($login);
    		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
    	} catch (Exception $e) {
    		http_response_code(400);
    		echo(json_encode($e->getErrors()));
    	}
    }
    
    public function get($login)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        } 

	    $user = $this->userDAO->findByID($login);
        if ($user == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
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
        
        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($user_array));        
        
    }

    public function login($login)
    {
        $currentLogged = parent::authenticateUser();
        if($currentLogged->getLogin() != $login) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to login as anyone but you");
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
            echo("Hello ".$login);
        }
        
    }
    
    
	
}

$userRest = new UserRest();
\URIDispatcher::getInstance()
    ->map("GET", "/users/$1", array($userRest, "get"))
    ->map("POST", "/users/login/$1", array($userRest, "login"))
    ->map("POST", "/users", array($userRest, "create"))
    ->map("PUT", "/users/$1", array($userRest, "update"))
    ->map("DELETE", "/users/$1", array($userRest, "delete"));
	
?>
