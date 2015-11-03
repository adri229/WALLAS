<?php

namespace model;

use database;
abstract class Model
{
	protected $dao;
	
	public function __construct()
	{
		//Obtiene el nombre del modelo a partir de la clase 
		//que herede de este modelo abstracto.
		//example: User
		//get_called_class(): models\User
		//strstr(get_called_class(), "\\"): \User
		//substr(strstr(get_called_class(), "\\"), 1); User
		$model = substr(strstr(get_called_class(), "\\"), 1);
		
		
		//obtiene el dao pasandole el nombre de la entidad a DAOFactory
		$this->dao = \database\DAOFactory::getDAO($model);
	}
	
	
	public abstract static function findBy($where);
	
	public abstract function getEntity();
	
	public abstract function save();
	
	public abstract function delete();
	
	public abstract function validate();
	
	
	
	
	
	
	
	
	
}
?>