<?php
// file: /controller/CommentsController.php
require_once (__DIR__ . "/../model/User.php");
require_once (__DIR__ . "/../model/Post.php");
require_once (__DIR__ . "/../model/Comment.php");
require_once (__DIR__ . "/../database/PostDAO.php");
require_once (__DIR__ . "/../database/CommentDAO.php");
require_once (__DIR__ . "/../controller/BaseController.php");

/**
 * Class CommentsController
 *
 * Controlador relativo a los comentarios cuya funcionalidad
 * es insertar un nuevo comentario en un post.
 *
 * @author jenifer <jeny-093@hotmail.com>
 * @author adrian <adricelixfernandez@gmail.com>
 */
class CommentsController extends BaseController {
	
	/**
	 * Referencia a la clase CommentDAO que interactua con la BD
	 *
	 * @var CommentDAO
	 */
	private $commentDAO;
	
	/**
	 * Referencia a la clase PostDAO que interactua con la BD
	 *
	 * @var PostDAO
	 */
	private $postDAO;
	public function __construct() {
		parent::__construct ();
		$this->commentDAO = new CommentDAO ();
		$this->postDAO = new PostDAO ();
	}
	
	/**
	 * Metodo del controlador Comments cuya funcionalidad es insertar un comentario
	 * en un post.
	 * Verifica que el usuario ha iniciado sesion y que el post existe
	 *
	 * @throws Exception Si el usuario no inicio sesion
	 * @return void
	 */
	public function add() {
		if (! isset ( $this->currentUser )) {
			throw new Exception ( "Not in session. Adding comments requires login" );
		}
		
		if (isset ( $_GET ["id"] )) { // reaching via HTTP Post...
		                              
			// Se obtiene el post de la BD
			$idPost = $_GET ["id"];
			$post = $this->postDAO->findByIdPost ( $idPost );
			
			// Si no existe el post lanza una excepcion
			if ($post == NULL) {
				throw new Exception ( "no such post with id: " . $idPost );
			}
			
			// Crea el objeto Comment
			$comment = new Comment ();
			$comment->setDate ( date ( "Y-m-d H:i:s" ) );
			$comment->setContent ( $_POST ["content"] );
			$comment->setAuthor ( $this->currentUser->getEmail () );
			$comment->setIdPost ( $post->getIdPost () );
			
			try {
				// Valida el comentario, si falla lanza una excepcion
				$comment->checkIsValidForCreate ();
				
				// Guarda el comentario en la BD
				$this->commentDAO->save ( $comment );
				
				// Redirige al post
				$this->view->redirect ( "posts", "viewPosts", "id=" . $post->getIdPost () );
			} catch ( ValidationException $ex ) {
				$errors = $ex->getErrors ();
				
				$this->view->setVariable ( "comment", $comment, true );
				$this->view->setVariable ( "errors", $errors, true );
				
				$this->view->redirect ( "posts", "viewPosts", "id=" . $post->getIdPost () );
			}
		} else {
			throw new Exception ( "No such post id" );
		}
	}
}