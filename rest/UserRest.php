<?php

/*require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");
require_once(__DIR__."/BaseRest.php");*/
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
        $user = new \User($data->login, $data->password, $data->fullname, 
                $data->email, $data->phone, $data->address, $data->country);
        
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
    
    /*
    public function update($login, $data) 
    {
    	$currentUser = parent::authenticateUser();
    	if ($login != $currentUser->getLogin()) {
    		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
    		echo("You are not authorized to access this resource");
    	} 
    		
    	$user = $this->userDAO->findByID($login);
    	if ($user == NULL) {
    		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
    		echo("User with id ".$login." not found");
    	}
    	
    	$user->setEmail($data->email);
    	$user->setPhone($data->phone);
    	$user->setAddress($data->address);
    	$user->setCountry($data->country);
        
        try {
            //$user->validate();
            $this->userDAO->update($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            //header("Location: ".$_SERVER['REQUEST_URI']);
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
        
    }
    */

    private function update($login, $data)
    {
        $user = $this->userDAO->findByID($login);
        if ($user == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo("User with id ".$login." not found");
        }
        return $user;
    }

    public function updatePassword ($login,$password)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            //return;
        }

        $user = $this->update($login,$password);
        $user->setPassword($password->password);

         try {
            //$user->validate();
            $this->userDAO->updatePassword($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }

    public function updateEmail ($login, $email)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->update($login,$email);
        $user->setEmail($email->email);

         try {
            //$user->validate();
            $this->userDAO->updateEmail($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }


    public function updatePhone ($login, $phone)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->update($login, $phone);
        $user->setPhone($phone->phone);

         try {
            //$user->validate();
            $this->userDAO->updatePhone($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }


    public function updateAddress ($login, $address)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->update($login,$address);
        $user->setAddress($address->address);

         try {
            //$user->validate();
            $this->userDAO->updateAddress($user);
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        } catch (Exception $e) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }

    public function updateCountry ($login, $country)
    {
        $currentUser = parent::authenticateUser();
        if ($login != $currentUser->getLogin()) {
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            echo("You are not authorized to access this resource");
            return;
        }

        $user = $this->update($login,$country);
        $user->setCountry($country->country);

         try {
            //$user->validate();
            $this->userDAO->updateCountry($user);
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
        }

	    $user = $this->userDAO->findByID($login);
        if ($user == NULL) {
            header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
            echo ("User with login ".$login." not found");
        }
        

        $user_array = array(
            "login" => $user->getLogin(),
            "password" => $user->getPassword(),
            "fullname" => $user->getFullname(),
            "email" => $user->getEmail(),
            "phone" => $user->getPhone(),
            "address" => $user->getAddress(),
            "country" => $user->getCountry()
        );
        
        header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
        header('Content-Type: application/json');
        echo(json_encode($user_array));        
        
    }
/*
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
  */  
    
	
}

$userRest = new UserRest();
\URIDispatcher::getInstance()
	->map("GET", "/users/$1", array($userRest, "get"))
	->map("POST", "/users", array($userRest, "create"))
	->map("PUT", "/users/$1", array($userRest, "update"))
    ->map("PUT", "/users/$1/password", array($userRest, "updatePassword"))
    ->map("PUT", "/users/$1/email", array($userRest, "updateEmail"))
    ->map("PUT", "/users/$1/phone", array($userRest, "updatePhone"))
    ->map("PUT", "/users/$1/address", array($userRest, "updateAddress"))
    ->map("PUT", "/users/$1/country", array($userRest, "updateCountry"))
	->map("DELETE", "/users/$1", array($userRest, "delete"));
	

?>
