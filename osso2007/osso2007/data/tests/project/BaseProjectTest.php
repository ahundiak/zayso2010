<?php
require_once 'PHPUnit/Framework/TestCase.php';

class BaseProjectTest extends PHPUnit_Framework_TestCase
{
    protected $context = NULL;
    protected $db      = NULL;
    
    /* Does get called for each individual test but oh well */
    protected function setUp()
    {
        $this->context = new ApplicationContext();
        $this->db      = $this->context->db;
    }
}

?>
