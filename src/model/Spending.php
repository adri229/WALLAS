<?php

class Spending {
	private $idSpending;
	private $dateSpending;
	private $quantity;
	private $user;
	
	public function __construct($idSpending=NULL, 
                                    $dateSpending=NULL, 
                                    $quantity=NULL, 
                                    $user=NULL) {
            $this->idSpending=idSpending;
            $this->dateSpending=dateSpending;
            $this->quantity=quantity;
            $this->user=user;
        }
        
        public function getIdSpending(){
            return $this->idSpending;
        }
        
        public function setIdSpending($idSpending){
            $this->idSpending=$idSpending;
        }
        
        public function getDateSpending(){
            return $this->dateSpending;
        }
        
        public function setDateSpending($dateSpending){
            $this->dateSpending=$dateSpending;
        }
        
        public function getQuantity(){
            return $this->dateSpending;
        }
        
        public function setQuantity($quantity){
            $this->quantity=$quantity;
        }
        
        public function getUser(){
            return $this->user;
        }
        
        public function setUser($user){
            $this->user=$user;
        }
        
        
}
?>
