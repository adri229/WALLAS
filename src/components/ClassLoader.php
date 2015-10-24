<?php

namespace components;

class ClassLoader {

    private $fileExtension = '.php';
    private $namespace;
    private $includePath;
    private $namespaceSeparator = '\\';

    
    public function __construct($ns = null, $includePath = null){
        $this->_namespace = $ns;
        $this->_includePath = $includePath;
    }

    public function register(){
        spl_autoload_register(array($this,'loadClass'));
    }
    
    public function unregister(){
        spl_autoload_unregister(array($this,'loadClass'));
    }

    public function loadClass($className){
        $ns = $this->namespace . $this->namespaceSeparator;
        
        if($this->namespace === null || $ns === substr())
    
    



    }
}
?>
