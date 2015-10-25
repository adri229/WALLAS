<?php

namespace database;


abstract class SQLDAO implements DAO
{
    protected $db;
    protected $tableName;  // nombre de la tabla de la BD SQL con que trabajar

    /**
     * Constructor abstracto, almacena el nombre de la tabla proporcionado para 
     * trabajar con ella.
     *
     * @param String $tableName
     *     Nombre de la tabla de la BD SQL con la que esta instancia de SQLDAO 
     *     trabajara.
     */
    public function __construct($tableName)
    {
    	$this->db = PDOConection::getInstance();
        $this->tableName = $tableName;
    }

    /**
     * Implementa la insercion de datos de la interfaz DAO.
     *
     * @see DAO
     */
    public function insert($data)
    {
        $query = "INSERT INTO " . $this->tableName . "(";
        $itr = new \CachingIterator(new \ArrayIterator($data));
        foreach ($this->attributes as $key => $value) {
        	$query .= $value;
        	if ($itr->hasNext()) $query .= ",";
        }
        $query .= ") VALUES (" . str_repeat("?, ", count($data)-1) . " ?)";
	    
	    $stmt = $this->db->prepare($query);
	    
	    $i = 1;
	    foreach ($data as $key => $value) {
	    	$stmt->bindParam($i++, $data[$key]);
	    }
        
	    return $stmt->execute();
    }

    /**
     * Implementa la modificacion de datos de la interfaz DAO.
     *
     * @see DAO
     */
    public function update($data, $where = null)
    {
        $query = "UPDATE " . $this->tableName . " SET ";
        $itr = new \CachingIterator(new \ArrayIterator($data));
        foreach ($itr as $key => $value) {
            $query .= $key . " = ?";
            if ($itr->hasNext()) $query .= ", ";
        }
        if (isset($where)) {
        	$query .= " WHERE ";
        	$itr = new \CachingIterator(new \ArrayIterator($data));
        	foreach ($itr as $key => $value) {
        		$query .= $key . " ?";
        		if ($itr->hasNext()) $query .= " AND ";
        	}
        }
        
        $stmt = $this->db->prepare($query);
        
        $i = 1;
        foreach ($data as $key => $value) 
        	$stmt->bindParam($i++, $data[$key]);
        if (isset($where)){
        	foreach ($where as $key => $value) {
        		$stmt->bindParam($i++, $where[$key]);
        	}
        }
        return $stmt->execute();
        
    }

    /**
     * Implementa la eliminacion de datos de la interfaz DAO.
     *
     * @see DAO
     */
    public function delete($where)
    {
       $query = "DELETE FROM " . $this->tableName . " ";
       if (isset($where)){
       		$itr = new \CachingIterator(new \ArrayIterator($where));
       		foreach ($itr as $key => $value) {
       			$query .= $key . " ?";
       			if ($itr->hasNext()) $query .= " AND ";
       		}
       }
       
       $stmt = $this->db->prepare($query);
       
       $i = 1;
       if (isset($where)){
       		foreach ($where as $key => $value) {
       			$stmt->bindParam($i++, $where[$key]);
       		}
       }
       
       return $stmt->execute();
    }

    /**
     * Implementa la consulta de datos de la interfaz DAO.
     *
     * @see DAO
     */
    public function select($data, $where = null)
    {
        $query = "SELECT ";
        $itr = new \CachingIterator(new \ArrayIterator($data));
        foreach ($itr as $attribute) {
        	$query .= $attribute;
        	if ($itr->hasNext()) $query .= ", ";
        }
        $query = " FROM " . $this->tableName . " WHERE ";
        
    	if (isset($where)){
    		$itr = new \CachingIterator(new \ArrayIterator($data));
    		foreach ($itr as $key => $value) {
    			$query .= $key;
    			if ($itr->hasNext()) $query .= " AND ";
    		}
    	}
    	
    	$stmt = $this->db->prepare($query);
    	
    	if (isset($where)){
    		$i = 1;
    		foreach ($where as $key => $value) {
    			$stmt->bindParam($i++, $where[$key]);
    		}
    	}
    	
    	$result = array();
    	if ($stmt->execute()) {
    		while ($row = $query->fetch) {
    			$result[] = $row;
    		}
    	}
    	
    	return $result;
    }
}
?>