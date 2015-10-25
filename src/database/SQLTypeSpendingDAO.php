<?php

namespace database;

class SQLTypeSpendingDAO extends SQLDAO implements DAO
{
	public function __construct()
	{
		parent::__construct("TYPE_SPENDING");
	}
	
}
?>