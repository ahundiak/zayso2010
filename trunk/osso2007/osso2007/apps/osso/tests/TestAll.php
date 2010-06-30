<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
     define ('PHPUnit_MAIN_METHOD', 'TestAll::main');
}
require_once 'TestConfig.php';

/* Build an application context */

/* --------------------------------------------
 * Now for the testing framework
 */
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'ExportScheduleTest.php';
require_once 'ImportRefereeTest.php';
require_once 'ImportScheduleMadisonTest.php';

class TestAll
{
    public static function main()
    {
        $parameters = array();

        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('App Tests');

//        $suite->addTestSuite('ExportScheduleTest');
//        $suite->addTestSuite('ImportRefereeTest');
//        $suite->addTestSuite('ImportScheduleTest');
        $suite->addTestSuite('ImportWinter2008MadisonTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'TestAll::main') { TestAll::main(); }
