<?php
//file /controller/LikesController.php

require_once(__DIR__."/../model/Like.php");
require_once(__DIR__."/../database/PostDAO.php");
require_once(__DIR__."/../database/LikeDAO.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
 * Class LikesController
 * Controlador relativo a los likes de un post cuya funcionalidad es 
 * insertar un like en un post
 * 
 * @author jenifer <jeny-093@hotmail.com>
 * @author adrian <adricelixfernandez@gmail.com>
 */

class LikesController extends BaseController{
	
	/**
	 * Referencia a la clase LikeDAO que interactua con la BD
	 * @var likeDAO
	 */
	private $likeDAO;
	
	/**
	 * Referencia a la clase PostDAO que interactua con la BD
	 * @var postDAO 
	 */
	private $postDAO;
	
	public function __construct(){
		parent::__construct();
		
		$this->likeDAO = new LikeDAO;
		$this->postDAO = new PostDAO();
	}
	
	/**
	 * Metodo del controlador Likes cuya funcionalidad es insertar un like en un
	 * post. Verifica que el usuario haya iniciado sesion, que el post existe y
	 * que el usuario no ha hecho like previamente sobre ese post.
	 *
	 * @return void
	 */
	public function addLike(){
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing posts requires login");
		}
		
		if (isset($_GET["id"])) { 
			
			// Get the Post object from the database
			$idPost = $_GET["id"];
			$post = $this->postDAO->findByIdPost($idPost);
			
			//Si no existe un post con ese id lanza una excepcion
			if ($post == NULL) {
				throw new Exception("No such post with id: ".$idPost);
			}
			
			//Si el usuario ya hizo like en el post lanza una excepcion
			if($this->likeDAO->isNewLike($this->currentUser,$idPost) > 0){
				throw new Exception("This user already made like in this post");
			}
			
			//Create the Like Object
			$like = new Like($this->currentUser->getEmail(),$post);
			
			try {
				$this->likeDAO->addLikePost($like);
				$this->likeDAO->increaseNumLikes($idPost);
				if(isset($_GET["iduser"])){				
					$this->view->redirect("posts", "perfil", "id=".$_GET["iduser"]);
				}
				$this->view->redirect("posts", "viewPosts");
			} catch (ValidationException $ex){
				$errors = $ex->getErrors();
			
				$this->view->setVariable("like", $like, true);
				$this->view->setVariable("errors", $errors, true);
					
				$this->view->redirect("posts", "viewPosts");
			}
		} else {
			throw new Exception("No such post id");
		}

	}
	
	/**
	 * Metodo del controlador Likes cuya funcionalidad es eliminar un like en un
	 * post. Verifica que el usuario haya iniciado sesion, que el post existe y
	 * que el usuario no ha hecho like previamente sobre ese post.
	 *
	 * @return void
	 */
	public function removeLike(){
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing posts requires login");
		}
		
		if (isset($_GET["id"])) { 
			
			// Get the Post object from the database
			$idPost = $_GET["id"];
			$post = $this->postDAO->findByIdPost($idPost);
			
			//Si no existe un post con ese id lanza una excepcion
			if ($post == NULL) {
				throw new Exception("No such post with id: ".$idPost);
			}
			
			//Si el usuario ya hizo like en el post lanza una excepcion
			if($this->likeDAO->isNewLike($this->currentUser,$idPost) > 0){
				
				//Create the Like Object
				$like = new Like($this->currentUser->getEmail(),$post);
				
				try {
					$this->likeDAO->removeLikePost($like);
					$this->likeDAO->decreaseNumLikes($idPost);
					if(isset($_GET["iduser"])){				
					$this->view->redirect("posts", "perfil", "id=".$_GET["iduser"]);
				}
					$this->view->redirect("posts", "viewPosts");
				} catch (ValidationException $ex){
					$errors = $ex->getErrors();
				
					$this->view->setVariable("like", $like, true);
					$this->view->setVariable("errors", $errors, true);
						
					$this->view->redirect("posts", "viewPosts");
				}
			}else{
				throw new Exception("Este usuario no hizo like en el post todavia");
			}
		} else {
			throw new Exception("No such post id");
		}

	}
	
}