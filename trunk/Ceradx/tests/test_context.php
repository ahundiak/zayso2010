<?php

require_once 'PHPUnit/Framework/TestCase.php';

class TestRegistry extends Cerad_Registry
{
	function getDb()
    {
        if (!$this->db) {
            $dbParams = TestConfig::$dbParams;
            $this->db = new Cerad_DatabaseAdapter($dbParams);  
        }
        return $this->db;
    }
}
class TestContext
{
	function __construct()
	{
		$this->registry = new TestRegistry();
	}
	function getDb()       { return $this->registry->getDb(); }
	function getRegistry() { return $this->registry; }
	
	function setParams($params) { $this->registry->set('params',$params); }
	
}
class BaseModelTest extends PHPUnit_Framework_TestCase
{
    protected $context = NULL;
    
    /* Gets called for each individual test */
    protected function setUp()
    {
    	$this->context = $context = new TestContext();
    }
}

?>
