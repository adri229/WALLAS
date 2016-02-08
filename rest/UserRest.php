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
        $user = new \User($data->login, $data->password, $data->fullname, 
                $data->email, $data->phone, $data->address, $data->country);
        
        try {
            $user->validate();
            $this->userDAO->save($user);
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
        } catch (Exception $ex) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }
    
    
    public function update($data) 
    {
        $user = new \User($data->login, $data->password, $data->fullname, 
                $data->email, $data->phone, $data->address, $data->country);
        
        try {
            $user->validate();
            $this->userDAO->update($user);
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
        } catch (Exception $ex) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
        
    }
    
    public function updatePassword ($data)
    {
        $user = new \User($data->login, $data->password);
        
        try {
            $user->validate();
            $this->userDAO->save($user);
            header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
        } catch (Exception $ex) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }
    
    public function delete ($data) 
    {
        $user = new User($data->login(), $data->password);
        try {
            $this->userDAO->delete($login);
            header($_SERVER['SERVER_PROTOCOL'].' 204 Created');
            header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
        } catch (Exception $ex) {
            http_response_code(400);
            echo(json_encode($e->getErrors()));
        }
    }
    
    public function get($login)
    {
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
        echo(json_encode($post_array));        
        
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
	->map("GET", "/user/$1", array($userRest, "login"))
	->map("POST", "/user", array($userRest, "create"))
	->map("PUT", "/user/$1", array($userRest, "update"))
	->map("DELETE", "/user/$1", array($userRest, "delete"))
	->map("GET", "/user/$1", array($userRest, "get"));

?>
