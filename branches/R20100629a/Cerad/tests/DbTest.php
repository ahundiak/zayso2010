<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class DbTest extends PHPUnit_Framework_TestCase
{
  function testCreateDatabase()
  {
    $dbParams = array
    (
      'host'     => '127.0.0.1',
      'username' => 'root',
      'password' => 'root894',
      'dbname'   => 'test',  // Always seem to need something to connect to
      'dbtype'   => 'mysql',
    );
    $db = new Cerad_DatabaseAdapter($dbParams);

    $dbName = 'test01';
    $db->exec("DROP DATABASE IF EXISTS $dbName;");
    $db->exec("CREATE DATABASE $dbName;");
    $db->exec("USE $dbName;");

    $row = $db->fetchRow('SELECT DATABASE();');
    $this->assertEquals($dbName,$row['database()']);

    // Executing a source with pdo does not seem to work
    //$db->exec('SOURCE /home/ahundiak/zayso2010/Cerad/tests/DbTest.sql;');
    system('/xampp/mysql/bin/mysql -uroot -proot894 test01 < DbTest.sql');

    $rows = $db->fetchRows('DESCRIBE person;');
    $this->assertEquals(4,count($rows));

    $count = (int)$db->fetchCol('SELECT count(*) FROM person;');
    $this->assertEquals(3,$count);

    $row = $db->find('person','person_id',2);
    $this->assertEquals('Ethan',$row['fname']);

    $item = array('region_id' => 1, 'fname' => 'Mike','lname' => 'Petersen');
    $count = $db->insert('person','person_id',$item);
    $this->assertEquals(1,$count);
    
    $count = (int)$db->fetchCol('SELECT count(*) FROM person;');
    $this->assertEquals(4,$count);

    $item = array('person_id' => 4, 'fname' => 'Mikey','lname' => 'Petey');
    $count = $db->update('person','person_id',$item);
    $row = $db->find('person','person_id',4);
    $this->assertEquals('Petey',$row['lname']);

    $count = $db->delete('person','person_id',2);
    $this->assertEquals(1,$count);
    $count = $db->delete('person','person_id',2);
    $this->assertEquals(0,$count);
    $count = $db->delete('person','person_id',array(1,4));
    $this->assertEquals(2,$count);

    $count = (int)$db->fetchCol('SELECT count(*) FROM person;');
    $this->assertEquals(1,$count);

    $params = array('region_id' => array(1,4));
    $rows = $db->fetchRows('SELECT * FROM person2 WHERE region_id IN(:region_id);',$params);
    $this->assertEquals(3,count($rows));

  }
}
?>
