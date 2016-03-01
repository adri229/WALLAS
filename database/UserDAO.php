<?php
require_once (__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");


class UserDAO {
	
	/**
	 * Referencia a la conexion PDO
	 * 
	 * @var PDO
	 */
	private $db;
	public function __construct() {
            $this->db = PDOConnection::getInstance ();
	}
	
	/**
	 * Guarda un usuario en la base de datos
	 *
	 * @param User $user
	 *        	El usuario a ser guardado
	 * @throws PDOException si ocurre algun error en la BD
	 * @return void
	 */
	public function save(User $user) {
            $stmt = $this->db->prepare ( "INSERT INTO `USER` (`login`,`password`,`fullname`,
	    	`email`,`phone`,`address`,`country`) VALUES (?,?,?,?,?,?,?)" );
            $stmt->execute ( array (
		$user->getLogin(),
		$user->getPassword(),
                $user->getFullName(),
                $user->getEmail(),
		$user->getPhone(),
		$user->getAddress(),
		$user->getCountry() 
            ) );
	}
	
	/**
	 * Encuentra un usuario en la base de datos con su email.
	 *
	 * @param String $useremail
	 *        	El email del usuario
	 * @throws PDOException si ocurre algun error en la BD
	 * @return User instancia del objeto User
	 */
	public function findByID($login) {
            $stmt = $this->db->prepare ( "SELECT * FROM USER WHERE login=?" );
            $stmt->execute ( array (
		$login 
            ));
            $user = $stmt->fetch ( PDO::FETCH_ASSOC );
	
            if (! sizeof ( $user ) == 0) {
		return new User ( $user ["login"], $user ["password"], $user ["fullname"], $user ["email"], $user ["phone"], $user ["address"], $user ["country"] );
            } else {
		return NULL;
            }
	}
	
	/**
	 * Comprueba si el email y la password
	 * son validos para hacer login
	 *
	 * @param String $email
	 *        	El email del usuario
	 * @param String $password
	 *        	El email del usuario
	 * @throws PDOException si ocurre algun error en la BD
	 * @return boolean true si encuentra un usuario con ese email/password|false en caso contrario
	 */
	public function isValidUser($login, $password) {
            $stmt = $this->db->prepare ( "SELECT COUNT(login) FROM USER WHERE login=? and password=?" );
            $stmt->execute ( array (
            	$login,
		$password 
            ));
		
            if ($stmt->fetchColumn () > 0) {
		return true;
            }
	}
	


	public function isNewLogin($login) {
    	$stmt = $this->db->prepare("SELECT COUNT(login) FROM USER where login=?");
    	$stmt->execute(array($login));
    
    	if ($stmt->fetchColumn() == 0) {   
      		return true;
    	} 
  }

	/**
	 * Actualiza la password del usuario
	 *
	 * @param String $user
	 *        	El usuario
	 * @throws PDOException si ocurre algun error en la BD
	 */


	
	public function update($user)
	{
            $stmt = $this->db->prepare("UPDATE USER SET password = ?, email = ?, phone = ?,"
                . "address = ?, country = ? WHERE login = ?");
            $stmt->execute(array($user->getPassword(), $user->getEmail(), $user->getPhone(), 
            	$user->getAddress(), $user->getCountry(), $user->getLogin()));	
	}
	
	public function delete($login)
        {
            $stmt = $this->db->prepare("DELETE FROM USER WHERE login = ?");
            $stmt->execute(array($login));
        }
  
}
