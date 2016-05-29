<?php
/**
* 
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