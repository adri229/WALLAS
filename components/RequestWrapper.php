<?php
/**
 * Clase que proporciona un acceso en orientación a objetos de la
 * variable global $_GET, para obtener parámetros de peticiones GET.
 * 
 * @author acfernandez4 <acfernandez4@esei.uvigo.es>
 */

class RequestWrapper
{
	private $request;
	
	function __construct()
	{
		$this->request = $this->initFromHttp();
	}

	private function initFromHttp()
	{
		if (!empty($_GET)) return $_GET;
        return array();
	}

	public function getStartDate()
	{
		return $this->request["startDate"];
	}

	public function getEndDate()
	{
		return $this->request["endDate"];
	}
}
?>