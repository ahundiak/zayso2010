<?php

namespace Test;

use Doctrine\Common\Util\Debug;

class DbTests extends \PHPUnit_Framework_TestCase
{
  static $dbx;
  protected $db;

  public static function setUpBeforeClass()
  {
    $config = $GLOBALS['config'];
    $dbParams = $config['db_params'];
    self::$dbx = \Doctrine\DBAL\DriverManager::getConnection($dbParams);

  }
  public function setUp()
  {
    $this->db = self::$dbx;
  }
  function test1()
  {
    $this->assertTrue(true);
  }
  function test2()
  {
    $db = $this->db;
    $statement = $db->prepare('SELECT * FROM osso2007.openid;');
    $statement->execute();
    $items = $statement->fetchAll();

    $this->assertEquals(20,count($items));

    $item = $items[0];
  //Cerad_Debug::dump($item);

  }
  function test3()
  {
    $db = $this->db;
    $items = $db->fetchAll('SELECT * FROM osso2007.openid WHERE member_id IN(:member_id);',array('member_id' => 1));
    $this->assertEquals(2,count($items));

    $item = $items[0];
  //Doctrine\Common\Util\Debug::dump($item);

  }

  function test4()
  {
    $sm = $this->db->getSchemaManager();
    $dbs = $sm->listDatabases();
  //$this->assertEquals(8,count($dbs));
  //Debug::dump($dbs[1]);
  }
}
?>
