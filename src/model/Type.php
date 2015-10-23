<?php



class Type {
    
        private $idType;
        private $name;

	
	public function __construct($idType=NULL,$name=NULL) {
            $this->idType=idType;
            $this->name=name;
        }
        
        public function getidType(){
            return $this->idType;
        }
        
        public function setidType($idType){
            $this->idType=$idType;
        }
        
        public function getname(){
            return $this->name;
        }
        
        public function setname($name){
            $this->name=$name;
        }
        
       

}
?>
