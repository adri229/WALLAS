<?php

namespace database;

class SQLStockDAO extends SQLDAO implements DAO
{
	public function __construct()
	{
		parent::__construct("STOCK");
	}
	
}
?>