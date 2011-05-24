<?php

use Doctrine\Common\Util\Debug;

class DbTests extends BaseTests
{
  protected function init()
  {
    $dbParams = array
    (
      'dbname'   => 'osso2007',
      'user'     => 'impd',
      'password' => 'impd894',
      'host'     => 'localhost',
      'driver'   => 'pdo_mysql', // pdo_mysql
    );
    $this->db = \Doctrine\DBAL\DriverManager::getConnection($dbParams);

  }
  function test1()
  {
    $this->assertTrue(true);
  }
  function test2()
  {
    $db = $this->db;
    $statement = $db->prepare('SELECT * FROM openid;');
    $statement->execute();
    $items = $statement->fetchAll();

    $this->assertEquals(14,count($items));

    $item = $items[0];
  //Cerad_Debug::dump($item);

  }
  function test3()
  {
    $db = $this->db;
    $items = $db->fetchAll('SELECT * FROM openid WHERE member_id IN(:member_id);',array('member_id' => 1));
    $this->assertEquals(2,count($items));

    $item = $items[0];
  //Doctrine\Common\Util\Debug::dump($item);

  }
  function test4()
  {
    $sm = $this->db->getSchemaManager();
    $dbs = $sm->listDatabases();
    $this->assertEquals(9,count($dbs));
  //Debug::dump($dbs[1]);
  }
}
?>
