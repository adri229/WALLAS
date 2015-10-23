<?php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Clase User
 * 
 * @author adrian <adricelixfernandez@gmail.com>
 */
class User {
  
  private $email;
  private $password;
  private $fullname;
  private $phonenumber;
  private $address;
  private $country;
  
  
  public function __construct($email = NULL,
			      $password = NULL, 
			      $fullname = NULL, 
			      $phonenumber = NULL,
			      $address = NULL,
			      $country = NULL) {
      $this->email = $email;
      $this->password = $password;
      $this->fullname = $fullname;
      $this->numberphone = $phonenumber;
      $this->address = $address;
      $this->country = $country;
  }
  
  public function getEmail() {
      return $this->email;
  }
  
  public function setEmail($email) {
      $this->email = $email;
  }
  
  public function getPassword() {
      return $this->password;
  }  
    
  public function setPassword($password) {
      $this->password = $password;
  }
  
   public function getFullname(){
      return $this->fullname;
  }
  
  public function setFullname($fullname){
      $this->fullname = $fullname;
  }
 
  public function getPhoneNumber(){
      return $this->numberphone;
  }
  
  public function setPhoneNumber($phonenumber){
      $this->phonenumber = $phonenumber;
  }
  
  public function getAddress(){
      return $this->address;
  }
  
  public function setAddress($address){
      $this->address = $address;
  }
  
   public function getCountry(){
      return $this->country;
  }
  
  public function setCountry($fullname){
      $this->country = $country;
  }
  

  /**
   * Comprueba si el usuario actual es valido para 
   * ser insertado en la base de datos
   * 
   * @throws ValidationException si no es valido
   * 
   * @return void
   */  
  public function checkIsValidForRegister($repeat_password) {
      $errors = array();
      if (strlen($this->email) < 5) {
		$errors["email"] = "El email debe tener por lo menos 5 caracteres";
      }
      if (strlen($this->password) < 5) {
		$errors["password"] = "La contraseña debe tener por lo menos 5 caracteres";	
      }
	  if (strlen($this->name) < 5) {
		$errors["name"] = "El nombre debe tener por lo menos 5 caracteres";	
      }
	  if($this->password != $repeat_password) {
		$errors["repeat_password"] = "Las passwords no son iguales";	
	  }
      if (sizeof($errors)>0){
		throw new ValidationException($errors, "El usuario no es valido");
      }
  }


   /**
   * Comprueba si el usuario actual es valido para 
   * ser actualizado en la base de datos
   * 
   * @throws ValidationException si no es valido
   * 
   * @return void
   */  
  public function checkIsValidForUpdate($password2) {
      $errors = array();
      if (strlen($this->password) < 4) {
		$errors["password"] = "La password debe tener por lo menos 4 caracteres";	
      }
	  if($this->password != $password2) {
		$errors["password2"] = "Las password no son iguales";	
	  }
      if (sizeof($errors)>0){
		throw new ValidationException($errors, "El usuario no es valido");
      }
  }
}