<?php

namespace components;

class Session {

	public $sessionID;
	
	public function __construct(){
		session_start();
		$this->sessionID = session_id();
	}

	public function __destruct(){
		session_write_close();
	}

	public function destroySession(){
		session_unset();
		session_destroy();
	}

	public function __isset($key){
		return isset($_SESSION[$key]);
	}
	
	public function __get($key) {
		return $_SESSION[$key];
	}
	
	public function __set($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function __clone() {
		trigger_error("Cloning is forbidden for " . __CLASS__, E_USER_ERROR);
	}
}
?>