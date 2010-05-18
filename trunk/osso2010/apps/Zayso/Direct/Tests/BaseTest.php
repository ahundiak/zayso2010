<?php

require_once 'PHPUnit/Framework.php';

class BaseTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $dbMainParams = array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'osso2007',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    );
    $dbEaysoParams = array (
      'host'     => '127.0.0.1',
      'username' => 'impd',
      'password' => 'impd894',
      'dbname'   => 'eayso',
      'dbtype'   => 'mysql',
      'adapter'  => 'pdo_mysql'
    );
    $this->context = new Cerad_Context(array(
      'dbs' => array( 
        'dbMain'  => $dbMainParams,
        'dbEayso' => $dbEaysoParams,
       ),
    ));
  }
  protected function tearDown()
  {
    $this->context = NULL;
  }
}