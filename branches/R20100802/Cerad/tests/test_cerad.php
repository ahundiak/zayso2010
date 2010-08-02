<?php
if (!defined('PHPUnit_MAIN_METHOD')) define('PHPUnit_MAIN_METHOD','TestCerad::main');

require_once 'test_config.php';

/* Build an application context */

/* --------------------------------------------
 * Now for the testing framework
 */
require_once 'PHPUnit/Framework.php';

// require_once 'test_context.php';

class TestCerad
{
    public static function main()
    {
        $parameters = array();

        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Cerad Tests');

        $suite->addTestSuite('BasicTest');
        $suite->addTestSuite('DbTest');
      //$suite->addTestSuite('RefereeModelTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'TestCerad::main') { TestCerad::main(); }
?>