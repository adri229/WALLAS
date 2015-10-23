<?php
//file: controller/BaseController.php
require_once(__DIR__."/../model/Friend.php");
require_once(__DIR__."/../database/FriendDAO.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../core/I18n.php");
/**
 * Class BaseController
 *
 * Clase que implementa un Super Constructor basico del que heredan
 * todos los constructores de la aplicacion
 * 
 * @author jenifer <jeny-093@hotmail.com>
 * @author adrian <adricelixfernandez@gmail.com>
 */
class BaseController {
  /**
   * Instancia de ViewManager
   * @var ViewManager
   */
  protected $view;
  private $friendDAO; 
  
  /**
   * Instancia del usuario actual
   * @var User
   */
  protected $currentUser;
  
  public function __construct() {
    
    $this->view = ViewManager::getInstance();
    // get the current user and put it to the view
    if (session_status() == PHP_SESSION_NONE) {      
		session_start();
    }
    //inicializa la variable
    $this->friendDAO = new FriendDAO();
	
    if(isset($_SESSION["currentuser"])) {
	
	  //En la sesion de currentuser se encuentra todo el usuario 
	  //ya que al hacer el login se introdujo todo el usuario en la sesion
      $this->currentUser = $_SESSION["currentuser"];   
	  
      $this->view->setVariable("currentusername", $this->currentUser);
	  
	  //consigue el numero total de solicitudes de amistad
	  $numSolicitudes = $this->friendDAO->getNumSolicitudes($this->currentUser->getEmail());
	
	  //Carga el num solicitudes en la vista
	  $this->view->setVariable("numSolicitudes", $numSolicitudes);
	  
    }     
  }
}