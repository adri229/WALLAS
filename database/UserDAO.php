<?php
require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");

/**
 * Clase que gestiona el acceso a la base de datos del modelo User
 *
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class UserDAO {
	
	private $db;
	public function __construct() {
            $this->db = PDOConnection::getInstance ();
	}

	public function save(User $user) 
	{
        $stmt = $this->db->prepare ( "INSERT INTO `USER` (`login`,`password`,`fullname`,
	    	`email`,`phone`,`country`) VALUES (?,?,?,?,?,?)" );
        $stmt->execute(array(
			$user->getLogin(),
			$user->getPassword(),
            $user->getFullName(),
            $user->getEmail(),
			$user->getPhone(),
			$user->getCountry() 
        ));
	}
	
	public function findByID($login) {
        $stmt = $this->db->prepare ( "SELECT * FROM USER WHERE login=?" );
        $stmt->execute (array($login));
        $user = $stmt->fetch ( PDO::FETCH_ASSOC );
	
        if (! sizeof ( $user ) == 0) {
			return new User($user ["login"], $user["password"], $user["fullname"], 
				$user ["email"], $user["phone"], $user["country"] );
        } else {
			return NULL;
        }
	}
	
	public function isValidUser($login, $password) {
        $stmt = $this->db->prepare("SELECT * FROM USER WHERE login=?");
        $stmt->execute(array($login));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		
        if (!sizeof($user) == 0) {
			if (password_verify($password, $user['password'])) {
				return true;
			}
       } 
       return false;
        
	}

	public function isNewLogin($login) {
    	$stmt = $this->db->prepare("SELECT COUNT(login) FROM USER where login=?");
    	$stmt->execute(array($login));
    
    	if ($stmt->fetchColumn() == 0) {   
      		return true;
    	} 
    }

	public function update($user)
	{
		print_r($user);
        $stmt = $this->db->prepare("UPDATE USER SET password = ?, fullname = ?,"
            . "email = ?, phone = ?, country = ? WHERE login = ?");
        $stmt->execute(array($user->getPassword(), $user->getFullName(),
        	$user->getEmail(), $user->getPhone(), 
            $user->getCountry(), $user->getLogin()));	
	}
	
	public function delete($login)
    {
        $stmt = $this->db->prepare("DELETE FROM USER WHERE login = ?");
        $stmt->execute(array($login));
    }
  
}
