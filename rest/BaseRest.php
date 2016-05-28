<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../database/UserDAO.php");
require_once(__DIR__."/../components/ServerWrapper.php");

class BaseRest
{
    
    protected $server;

    public function __construct() { 
        $this->server = new ServerWrapper();
    }
    
    
    public function authenticateUser()
    {   
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="REST API of wallas"');
            die('This operation requires authentication');
        } 
        
        else {
            $userDAO = new UserDAO();
            if($userDAO->isValidUser(
                    $_SERVER['PHP_AUTH_USER'],
                    $_SERVER['PHP_AUTH_PW'])) {
                return new \User($_SERVER['PHP_AUTH_USER']);
            } else {
                header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
                header('WWW-Authenticate: Basic realm="REST API of wallas"');
                die('The username/password is not valid');
            }
       
        }
    }

}
?>
