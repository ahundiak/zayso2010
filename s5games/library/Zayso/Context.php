<?php
class Zayso_Context
{
	protected $db     = NULL;
	protected $repos  = NULL;
	protected $tables = NULL;

	function __construct($params = array())
	{
		
	}
	function __get($name)
	{
		switch($name) 
		{
			case 'db':     return $this->getDb();     break;
			case 'repos':  return $this->getRepos();  break;
			case 'tables': return $this->getTables(); break;
			
		}
	}
}
?>