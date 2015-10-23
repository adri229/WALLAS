<?php
require_once (__DIR__ . "/../database/UserDAO.php");
require_once (__DIR__ . "/../model/User.php");
require_once (__DIR__ . "/../core/ViewManager.php");
require_once (__DIR__ . "/../core/I18n.php");
require_once (__DIR__ . "/../controller/BaseController.php");
/**
 * Clase UsersController
 * 
 * Controla el inicio de sesion, cierre de sesion y registro
 * de usuarios.
 *  
 * @author adrian <adricelixfernandez@gmail.com>
 * @author jenifer <jeni093@gmail.com>
 */
class UsersController extends BaseController {
	
	/**
	 * Referencia a UserDAO para acceder a la base de datos
	 *
	 * @var userDAO
	 */
	private $userDAO;
	public function __construct() {
		parent::__construct ();
		
		// Inicializa la variable
		$this->userDAO = new UserDAO ();
	}
	
	/**
	 * Este metodo se llama cuando el usuario quiere hacer login
	 *
	 * @return void
	 */
	public function login() {
		if (isset ( $_POST ["email"] )) {
			
			// Si el usuario es valido
			if ($this->userDAO->isValidUser ( $_POST ["email"], $_POST ["password"] )) {
				
				// encuentra los datos del usuario para meterlos en la sesion (mete todo el objeto)
				$user = $this->userDAO->findByEmail ( $_POST ["email"] );
				$_SESSION ["currentuser"] = $user;
				
				// Envia al usuario al metodo posts del PostsController.
				$this->view->redirect ( "posts", "viewPosts" );
				
				// Si los datos introducidos no son validos devuelve error
			} else {
				$errors = array ();
				$errors ["emaillogin"] = "El email no es válido";
				$this->view->setVariable ( "errors", $errors );
			}
		}
		
		// utiliza el layout welcome.php
		$this->view->setLayout ( "welcome" );
		// renderiza la vista (/view/users/login.php)
		$this->view->render ( "users", "login" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario quiere registrarse
	 *
	 * @return void
	 */
	public function register() {
		$user = new User ();
		
		if (isset ( $_POST ["email"] )) {
			
			// Guarda los datos introducidos por POST en el objeto
			$user->setEmail ( $_POST ["email"] );
			$user->setPassword ( $_POST ["password"] );
			$user->setName ( $_POST ["name"] );
			
			try {
				// mira si los datos son correctos y si no lo son lanza excepcion
				$user->checkIsValidForRegister ( $_POST ["repeat_password"] );
				
				// Comprueba si el email ya esta registrado
				if (! $this->userDAO->emailExists ( $_POST ["email"] )) {
					
					// Guarda el usuario en la base de datos
					$this->userDAO->save ( $user );
					
					// Muestra mensaje de confirmacion
					$this->view->setFlash ( "Email " . $user->getEmail () . " añadido. Por favor, logueate ahora" );
					
					// Redirige al metodo login del controlador de usuarios
					$this->view->redirect ( "users", "login" );
					
					// Si el email ya esta registrado muestra un error
				} else {
					$errors = array ();
					$errors ["email"] = "Este email ya existe";
					$this->view->setVariable ( "errors", $errors );
				}
			} catch ( ValidationException $ex ) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors ();
				// And put it to the view as "errors" variable
				$this->view->setVariable ( "errors", $errors );
			}
		}
		
		// Guarda en user el contenido de $user
		$this->view->setVariable ( "user", $user );
		// layouts welcome.php
		$this->view->setLayout ( "welcome" );
		// renderiza la vista (/view/users/login.php)
		$this->view->render ( "users", "login" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario quiere modificar su contraseña
	 *
	 * @return void
	 */
	public function modificarContrasena() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "Not in session. Editing posts requires login" );
		}
		
		$user = new User ();
		
		if (isset ( $_POST ["password"] )) {
			
			// Guarda los datos introducidos por POST en el objeto
			$user->setEmail ( $this->currentUser->getEmail () );
			$user->setPassword ( $_POST ["password"] );
			
			try {
				// mira si los datos son correctos y si no lo son lanza excepcion
				$user->checkIsValidForUpdate ( $_POST ["password2"] );
				
				// Guarda el usuario en la base de datos
				$this->userDAO->updatePassword ( $user );
				
				// Redirige al metodo login del controlador de usuarios
				$this->view->redirect ( "posts", "viewPosts" );
			} catch ( ValidationException $ex ) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors ();
				// And put it to the view as "errors" variable
				$this->view->setVariable ( "errors", $errors );
			}
		}
		// renderiza la vista (/view/users/modificar.php)
		$this->view->render ( "users", "modificar" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario quiere cerrar sesion
	 *
	 * @return void
	 */
	public function logout() {
		session_destroy ();
		// redirige al metodo login del controlador de usuarios
		$this->view->redirect ( "users", "login" );
	}
}