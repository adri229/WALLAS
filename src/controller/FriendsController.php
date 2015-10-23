<?php
require_once (__DIR__ . "/../database/FriendDAO.php");
require_once (__DIR__ . "/../model/Friend.php");
require_once (__DIR__ . "/../model/User.php");
require_once (__DIR__ . "/../database/UserDAO.php");
require_once (__DIR__ . "/../core/ViewManager.php");
require_once (__DIR__ . "/../core/I18n.php");
require_once (__DIR__ . "/../controller/BaseController.php");
/**
 * Clase FriendsController
 *
 * @author adrian <adricelixfernandez@gmail.com>
 * @author jenifer <jeni093@gmail.com>
 */
class FriendsController extends BaseController {
	
	/**
	 * Referencia al FriendDAO para interactuar con la base de datos
	 *
	 * @var friendDAO
	 */
	private $friendDAO;
	public function __construct() {
		parent::__construct ();
		
		// inicializa la variable
		$this->friendDAO = new FriendDAO ();
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Aceptar"
	 * en las peticiones de amistad.
	 *
	 * @throws Exception si no hay una solicitud de amistad entre esos usuarios.
	 * @return void
	 */
	public function aceptarAmistad() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			// Obtiene el id del usuario al que acepta la solicitud
			$friendEmail = $_GET ["id"];
			
			// Encuentra la relacion de amistad de esta peticion en concreto
			$friendship = $this->friendDAO->findPeticion ( $this->currentUser, $friendEmail );
			
			// Si no existe la petición surge una excepción
			if ($friendship == NULL) {
				throw new Exception ( "no hay ninguna relacion entre esos usuarios: " );
			}
			
			// Actualiza la relación de amistad en la tabla friend con isFriend=1
			$this->friendDAO->updateIsFriend ( $friendship );
			
			// redirige al metodo solicitudes del controlador friends
			$this->view->redirect ( "friends", "solicitudes" );
		}
		
		// renderiza la vista (/view/friends/solicitudes.php)
		$this->view->render ( "friends", "solicitudes" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Rechazar" en las peticiones de amistad.
	 * @throws Exception si no hay ninguna solicitud de amistad entre los usuarios
	 * @return void
	 */
	public function rechazarAmistad() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			// Obtiene el id del usuario al que rechaza la solicitud
			$friendEmail = $_GET ["id"];
			
			// Encuentra la peticion de amistad en la tabla friends
			$friendship = $this->friendDAO->findPeticion ( $this->currentUser, $friendEmail );
			
			// Si no existe la peticion salta una excepcion
			if ($friendship == NULL) {
				throw new Exception ( "no hay ninguna relacion entre esos usuarios: " );
			}
			
			// Elimina la fila en friends
			$this->friendDAO->deleteFriendship ( $friendship );
			
			// redirige al metodo solicitudes del controlador friends
			$this->view->redirect ( "friends", "solicitudes" );
		}
		
		// render the view (/view/friends/solicitudes.php)
		$this->view->render ( "friends", "solicitudes" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Solicitar amistad"
	 * a un usuario.
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function solicitarAmistad() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			// Recupera el id del usuario al que pide amistad
			$friendEmail = $_GET ["id"];
			
			// Guarda la relacion en la base de datos con isFriend=0
			$this->friendDAO->saveFriedship ( $this->currentUser, $friendEmail );
			
			// redirige al metodo buscaramigos del controlador friends
			$this->view->redirect ( "friends", "buscaramigos" );
		}
		
		// renderiza la vista (/view/friends/buscaramigos.php)
		$this->view->render ( "friends", "buscaramigos" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Eliminar amigo"
	 * al visualizar la lista de amigos.
	 *
	 * @throws Exception si no hay ninguna relacion entre los usuarios
	 * @return void
	 */
	public function eliminarAmigo() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			// Recupera el id del usuario que elimina
			$friendEmail = $_GET ["id"];
			
			// encuentra la relacion de amistad entre los usuarios currentuser y friendEmail
			$friendship = $this->friendDAO->findFriendship ( $this->currentUser, $friendEmail );
			
			// Si no existe relación salta una excepcion
			if ($friendship == NULL) {
				throw new Exception ( "no hay ninguna relacion entre esos usuarios: " );
			}
			
			// Elimina la relacion de amistad
			$this->friendDAO->deleteFriendship ( $friendship );
			
			// redirige al metodo amigos del controlador friends
			$this->view->redirect ( "friends", "amigos" );
		}
		
		// renderiza la vista (/view/friends/amigos.php)
		$this->view->render ( "friends", "amigos" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Mis amigos"
	 * en la página de inicio. Se visualizan sus amigos
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function amigos() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		// find the Post object in the database
		$friends = $this->friendDAO->findFriends ( $this->currentUser );
		
		// Guarda en la variable friends el objeto $friends para visualizarlo en la vista
		$this->view->setVariable ( "friends", $friends );
		
		// renderiza la vista (view/friends/amigos.php)
		$this->view->render ( "friends", "amigos" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Buscar" en la página de inicio.
	 * Visualiza todos los usuarios que no tienen ninguna relacion de amistad 
	 * con el usuario actual
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function buscaramigos() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		// Encuentra los usuarios que no tienen relacion de amistad con currentuser
		$busquedas = $this->friendDAO->findUsuarios ( $this->currentUser );
		
		// Guarda en la variable busquedas el contenido de $busquedas para visualizarlo en la vista
		$this->view->setVariable ( "busquedas", $busquedas );
		
		// renderiza la vista (view/friends/buscar amigos)
		$this->view->render ( "friends", "buscaramigos" );
	}
	
	/**
	 * Este metodo se llama cuando el usuario pulsa "Solicitudes" en la 
	 * pagina de inicio. Se muestran las solicitudes de amistad.
	 *
	 * @throws Exception Si el usuario no inicio sesion 
	 * @return void
	 */
	public function solicitudes() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "No iniciaste sesion. Vete a login" );
		}
		
		// Encuentra las solicitudes de amistad para currentuser
		$solicitudes = $this->friendDAO->findSolicitudes ( $this->currentUser );
		
		// Guarda en la variable solicitudes el contenido de $solicitudes para mostrarlo en la vista
		$this->view->setVariable ( "solicitudes", $solicitudes );
		
		// renderiza la vista(view/friends/solicitudes)
		$this->view->render ( "friends", "solicitudes" );
	}
}