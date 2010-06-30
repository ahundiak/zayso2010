<?php
/* -------------------------------
 * 15 Jan 2008 Break out the importer into it's own class
 */
if (!defined('PHPUnit_MAIN_METHOD')) {
     define ('PHPUnit_MAIN_METHOD', 'TestImport::main');
}
require_once 'TestConfig.php';

/* Build an application context */

/* --------------------------------------------
 * Now for the testing framework
 */
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'models/ImportTest.php';

class TestImport
{
    public static function main()
    {
        $parameters = array();

        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Model Tests');

        $suite->addTestSuite('ImportTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'TestImport::main') { TestImport::main(); }
