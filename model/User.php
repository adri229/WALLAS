<?php

class User 
{
	private $login;
	private $hashedPass;
	private $fullname;
	private $email;
	private $phone;
	private $country;
	
	public function __construct($login = NULL, $hashedPass = NULL, $fullname = NULL, 
			$email = NULL, $phone = NULL, $country = NULL)
	{
		$this->login = strtolower($login);
		$this->hashedPass = $hashedPass;
		$this->fullname = $fullname;
		$this->email = $email;
		$this->phone = $phone;
		$this->country = $country;
	}
	
	public function getLogin()
	{
		return $this->login;
	}
	
	public function getPassword()
	{
		return $this->hashedPass;
	}
	
	public function getFullname()
	{
		return $this->fullname;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getPhone()
	{
		return $this->phone;
	}
	
        
    public function getCountry()
    {
        return $this->country;
    }

    public function setLogin($login)
	{
		$this->login = $login;
	}
	
	public function setPassword($password)
	{
		$this->hashedPass = $password;
	}
	
	public function setFullname($fullname)
	{
		$this->fullname = $fullname;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}
	
	public function setCountry($country)
	{
		$this->country = $country;
	}
}
?>
