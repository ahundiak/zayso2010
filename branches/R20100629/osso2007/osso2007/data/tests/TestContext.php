<?php
class ApplicationLoader
{
    static function loadClass($class)
    {
    }
}
spl_autoload_register(array('ApplicationLoader','loadClass'));
spl_autoload_register(array('ProjectLoader',    'loadClass'));
spl_autoload_register(array('Zend_Loader',      'loadClass'));
               
class ApplicationContext
{
    public $db  = NULL;
    public $pdo = NULL;
    public $dbParams = NULL;
        
    function __construct()
    {
        ProjectContext::setInstance($this);
        
        $this->dbParams = TestConfig::$dbParams;
    }
}
require_once 'PHPUnit/Framework/TestCase.php';

class BaseTest extends PHPUnit_Framework_TestCase
{
    protected $context = NULL;
    
    /* Does get called for each individual test but oh well */
    protected function setUp()
    {
        $this->context = new ApplicationContext();
    }
}
class BaseModelTest extends PHPUnit_Framework_TestCase
{
    protected $context = NULL;
    
    /* Does get called for each individual test but oh well */
    protected function setUp()
    {
        $this->context = $context = new ApplicationContext();
        
        $context->db     = new Proj_Db_Adapter($context->dbParams);
        
        $context->tables = new Proj_Locator_Table($context);
        
        $context->models = new Proj_Locator_Model($context);
    }
}

?>
