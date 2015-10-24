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
        
        if ($this->namespace === null || $ns === substr($className, 0, strlen($ns))){
        
            $filename = "";
            $lastNsPos = strripos($className,$this->namespaceSeparator);
        
            if ($lastNsPos !== false){
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $filename = str_replace($namespaceSeparator, DIRECTORY_SEPARATOR, $namespace);
                $filename .= DIRECTORY_SEPARATOR;
            }
        
        }
        
        $filename .= str_replace('_', DIRECTORY_SEPARATOR, $className);
        $filename .= $this->fileExtension;
        
        $require = "";
        if ($this->includePath !== null)
            $require .= $this->includePath . DIRECTORY_SEPARATOR;
        
        if (file_exists($require . $filename))
            require $require . $filename;
        
    }
}
?>
