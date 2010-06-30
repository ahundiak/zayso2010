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

require_once 'project/BaseProjectTest.php';
require_once 'project/AutoLoadTest.php';
require_once 'project/RouteTest.php';
require_once 'project/SqlTest.php';

require_once 'misc/AdapterTest.php';
require_once 'misc/TableTest.php';
require_once 'misc/LocatorTest.php';
require_once 'misc/StaticTest.php';

require_once 'models/PhoneModelTest.php';
require_once 'models/EmailModelTest.php';
require_once 'models/VolModelTest.php';
require_once 'models/UnitModelTest.php';
require_once 'models/PersonModelTest.php';
require_once 'models/PersonVolModelTest.php';
require_once 'models/TeamModelTest.php';
require_once 'models/FieldModelTest.php';
require_once 'models/EventModelTest.php';
require_once 'models/UserModelTest.php';
require_once 'models/DivisionModelTest.php';

require_once 'models/ImportTest.php';

class TestAll
{
    public static function main()
    {
        $parameters = array();

        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Model Tests');

        $suite->addTestSuite('AutoLoadTest');
        $suite->addTestSuite('RouteTest');
//      $suite->addTestSuite('SqlTest');
        
        $suite->addTestSuite('AdapterTest');
        $suite->addTestSuite('TableTest');
        $suite->addTestSuite('LocatorTest');
        $suite->addTestSuite('StaticTest');
        
        $suite->addTestSuite('PhoneModelTest');
        $suite->addTestSuite('EmailModelTest');
        $suite->addTestSuite('VolModelTest');
        $suite->addTestSuite('UnitModelTest');
        $suite->addTestSuite('PersonModelTest');
        $suite->addTestSuite('PersonVolModelTest');
        $suite->addTestSuite('TeamModelTest');
        $suite->addTestSuite('FieldModelTest');
        $suite->addTestSuite('EventModelTest');
        $suite->addTestSuite('UserModelTest');
        $suite->addTestSuite('DivisionModelTest');
        
//        $suite->addTestSuite('ImportTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'TestAll::main') { TestAll::main(); }
