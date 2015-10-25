<?php

namespace database;

class SQLTypeDAO extends SQLDAO implements DAO
{
	public function __construct()
	{
		parent::__construct("TYPE");
	}
	
}
?>