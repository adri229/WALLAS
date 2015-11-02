<?php
namespace model;


/**
 * Clase User
 *
 * @author adrian <adricelixfernandez@gmail.com>
 */
class User extends Model
{
    private $login;
    private $hashedPass;
    private $fullname;
    private $email;
    private $phoneNumber;
    private $address;
    private $country;

    public function __construct($login)
    {
        parent::__construct();
        
        $this->login = strtolower($login);
        $this->hashedPass = $hashedPass;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->country = $country;
    }
   
    
    
    public static function findBy($where)
    {
        $logins = \database\DAOFactory::getDAO("user")->select(["login"], $where);
        if (!$logins) return array();
        
        $objects = array();
        foreach ($logins as $login) {
            $user = new User($login["login"]);
            if (!$user->getEntity()) break;
            $objects[] = $user;
        }
        return $objects;
        
    }
    
    
    public function getEntity()
    {
        //Selecciona las filas cuyo login coincide 
        $rows = $this->dao->select(["*"], ["login" => $this->login]);
        if (!$rows) return false; 
        
        $this->hashedPass  = $rows[0]["password"];
        $this->fullname    = $rows[0]["fullname"];
        $this->email       = $rows[0]["email"];
        $this->phoneNumber = $rows[0]["phoneNumber"];
        $this->address     = $rows[0]["address"];
        $this->country     = $rows[0]["country"];
        
        return true;
    }
    
    public function save()
    {
        $data = ["login" => $this->login()];
        
        if (isset($this->hashedPass))   $data["password"]    = $this->hashedPass;
        if (isset($this->fullname))     $data["fullname"]    = $this->fullname;
        if (isset($this->email))        $data["email"]       = $this->email;
        if (isset($this->phoneNumber))  $data["phoneNumber"] = $this->phoneNumber;
        if (isset($this->address))      $data["address"]     = $this->address;
        if (isset($this->country))      $data["country"]     = $this->country;
        
        $numLogins =$this->dao->select(["COUNT(login)"], ["login" => $this->login])[0][0];
        
        if($numLogins == 0) return $this->dao->insert($data);
        elseif ($numLogins == 1) return $this->dao->update($data);
        return false;
    }
    
    public function delete()
    {
        return $this->dao->delete(["login" => $this->login]);
    }
    
    public function validate()
    {
        
    }
    
    
    public function getLogin()
    {
        return $this->login;
    }
    
    
    public function isNewLogin()
    {
        return $this->dao->select(["COUNT(login)"], ["login" => $this->login])[0][0] == 0;
        
    }
    
    /**
     * Password debe ser de una longitud superior a 8 caracteres
     * Debe ser alfanumerica, conteniendo al menos un numero
     * No debe contener caracteres especiales
     * @param unknown $cleanPass
     */
    public function setPassword($cleanPass)
    {
        if (strlen($cleanPass) < 8) return false;
        
        preg_match_all('/[0-9]/', $password, $numbers);
        if (count($numbers[0]) == 0) return false;
        
        preg_match_all('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $password, $specialchars);
        if (count($specialchars[0]) != 0) return false;
        
        $this->hashedPass = \components\Password::hash($cleanPass, PASSWORD_DEFAULT);
        return true;
    }
    
    
    public function checkPassword($cleanPass)
    {
        return components\Password::verify($cleanPass, $this->hashedPass);        
    }
    
    
    
    
    
}