<?php


namespace rest;

class BaseRest
{
    protected $session;
    
    public function __construct() { }
    
    
    public function authenticateUser()
    {   
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="REST API of wallas"');
            die('This operation requires authentication');
        } 
        
        else {
            $user = new \model\User($_SERVER['PHP_AUTH_USER']);
            $user->setPassword($_SERVER['PHP_AUTH_PW']);
            
            //Comprobar condicion: 
            if ($user->isNewLogin() || !$user->checkPassword($_SERVER['PHP_AUTH_PW'])) {
                header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
                header('WWW-Authenticate: Basic realm="REST API of wallas"');
                die('The username/password is not valid');
            } else {
                return $user;
            }
       
        }
    }

}
?>