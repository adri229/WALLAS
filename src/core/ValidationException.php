<?php
// file: core/ValidationException.php
/**
 * Class ValidationException
 *
 * Incluye un array de errores para la validacion de datos.
 *
 * El array de errores contiene validacion de errores.
 *
 * @author jenifer <jeny-093@hotmail.com>
 * @author adrian <adricelixfernandez@gmail.com>
 */
class ValidationException extends Exception {
	
	/**
	 * array de errores
	 * 
	 * @var mixed
	 */
	private $errors = array ();
	public function __construct(array $errors, $msg = NULL) {
		parent::__construct ( $msg );
		$this->errors = $errors;
	}
	
	/**
	 * Devuelve la validacion de errores
	 *
	 * @return mixed La validacion de errores
	 */
	public function getErrors() {
		return $this->errors;
	}
}