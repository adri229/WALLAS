<?php

namespace database;

class SQLSpendingDAO extends SQLDAO implements DAO
{
	
	public function __construct()
	{
		parent::__construct("SPENDING");
	}
	
}