<?php

class Stock {
    
        private $idStock;
        private $revenue;
	private $dateStock;
	private $user;
	
	public function __construct($idStock=NULL, 
                                    $revenue=NULL, 
                                    $dateStock=NULL,
                                    $total=NULL,
                                    $user=NULL) {
            $this->idStock=idStock;
            $this->revenue=revenue;
            $this->dateStock=dateStock;
            $this->total=total;
            $this->user=user;
        }
        
        public function getidStock(){
            return $this->idStock;
        }
        
        public function setidStock($idStock){
            $this->idStock=$idStock;
        }
        
        public function getrevenue(){
            return $this->revenue;
        }
        
        public function setrevenue($revenue){
            $this->revenue=$revenue;
        }
        
        public function getdateStock(){
            return $this->revenue;
        }
        
        public function setdateStock($dateStock){
            $this->dateStock=$dateStock;
        }
        
        public function getTotal(){
            return $this->total;
        }
        
        public function setTotal($total){
            $this->total=$total;
        }
        
        public function getUser(){
            return $this->user;
        }
        
        public function setUser($user){
            $this->user=$user;
        }
    


}
?>
