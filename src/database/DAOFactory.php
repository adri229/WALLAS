<?php

namespace database;

class DAOFactory
{

    public static function getDAO($entityName)
    {
        // extrae el nombre de la clase desde el fichero de configuracion
        $className = \components\Configuration::getValue("daos", strtolower($entityName));

        // crea y retorna un objeto de dicha clase
        if (isset($className)) {
            $className = "database\\" . $className;
            return new $className();
        }

        // en caso de no existir dicha entidad en el fichero de configuracion, 
        // retorna null
        return null;
    }

}


?>
