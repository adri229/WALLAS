<?php
// file /controller/LikesController.php
require_once (__DIR__ . "/../model/LikeComment.php");
require_once (__DIR__ . "/../database/CommentDAO.php");
require_once (__DIR__ . "/../database/LikeCommentDAO.php");
require_once (__DIR__ . "/../controller/BaseController.php");

/**
 * Class LikesCommentsController
 * Controlador relativo a los likes de un comentario cuyas funcionalidades
 * son insertar un like o eliminar un like en un comentario
 *
 * @author jenifer <jeny-093@hotmail.com>
 * @author adrian <adricelixfernandez@gmail.com>
 */
class LikesCommentsController extends BaseController {
	
	/**
	 * Referencia a la clase LikeDAO que interactua con la BD
	 * 
	 * @var likeDAO
	 */
	private $likeCommentDAO;
	
	/**
	 * Referencia a la clase commentDAO que interactua con la BD
	 * 
	 * @var commentDAO
	 */
	private $commentDAO;
	public function __construct() {
		parent::__construct ();
		
		$this->likeCommentDAO = new LikeCommentDAO ();
		$this->commentDAO = new CommentDAO ();
	}
	
	/**
	 * Metodo del controlador Likes cuya funcionalidad es insertar un like en un
	 * comment.
	 * Verifica que el usuario haya iniciado sesion, que el comment existe y
	 * que el usuario no ha hecho like previamente sobre ese comment.
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function addLike() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "Not in session. Editing comments requires login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			// Get the comment object from the database
			$idComment = $_GET ["id"];
			$comment = $this->commentDAO->findByIdComment ( $idComment );
			
			// Si no existe un comment con ese id lanza una excepcion
			if ($comment == NULL) {
				throw new Exception ( "No such comment with id: " . $idComment );
			}
			
			// Si el usuario ya hizo like en el comment lanza una excepcion
			if ($this->likeCommentDAO->isNewLike ( $this->currentUser, $idComment ) > 0) {
				throw new Exception ( "This user already made like in this comment" );
			}
			
			// Create the Like Object
			$like = new LikeComment ( $this->currentUser->getEmail (), $comment );
			
			try {
				$this->likeCommentDAO->addLikecomment ( $like );
				$this->likeCommentDAO->increaseNumLikes ( $idComment );
				if (isset ( $_GET ["iduser"] )) {
					$this->view->redirect ( "posts", "perfil", "id=" . $_GET ["iduser"] );
				}
				$this->view->redirect ( "posts", "viewPosts" );
			} catch ( ValidationException $ex ) {
				$errors = $ex->getErrors ();
				
				$this->view->setVariable ( "likecomments", $like, true );
				$this->view->setVariable ( "errors", $errors, true );
				
				$this->view->redirect ( "posts", "viewPosts" );
			}
		} else {
			throw new Exception ( "No such comment id" );
		}
	}
	
	/**
	 * Metodo del controlador Likes cuya funcionalidad es eliminar un like en un
	 * comment.
	 * Verifica que el usuario haya iniciado sesion, que el comment existe y
	 * que el usuario no ha hecho like previamente sobre ese comment.
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function removeLike() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "Not in session. Editing comments requires login" );
		}
		
		if (isset ( $_GET ["id"] )) {
			
			// Get the comment object from the database
			$idComment = $_GET ["id"];
			$comment = $this->commentDAO->findByIdComment ( $idComment );
			
			// Si no existe un comment con ese id lanza una excepcion
			if ($comment == NULL) {
				throw new Exception ( "No such comment with id: " . $idComment );
			}
			
			// Si el usuario ya hizo like en el comment lanza una excepcion
			if ($this->likeCommentDAO->isNewLike ( $this->currentUser, $idComment ) > 0) {
				
				// Create the Like Object
				$like = new LikeComment ( $this->currentUser->getEmail (), $comment );
				
				try {
					$this->likeCommentDAO->removeLikecomment ( $like );
					$this->likeCommentDAO->decreaseNumLikes ( $idComment );
					if (isset ( $_GET ["iduser"] )) {
						$this->view->redirect ( "posts", "perfil", "id=" . $_GET ["iduser"] );
					}
					$this->view->redirect ( "posts", "viewPosts" );
				} catch ( ValidationException $ex ) {
					$errors = $ex->getErrors ();
					
					$this->view->setVariable ( "like", $like, true );
					$this->view->setVariable ( "errors", $errors, true );
					
					$this->view->redirect ( "posts", "viewPosts" );
				}
			} else {
				throw new Exception ( "Este usuario no hizo like en el post todavia" );
			}
		} else {
			throw new Exception ( "No such comment id" );
		}
	}
}