<?php

namespace Test;

class ServicesTests extends \PHPUnit_Framework_TestCase
{
  static    $servicesx;
  protected $services;

  public static function setUpBeforeClass()
  {
    $config = $GLOBALS['config'];

    self::$servicesx = new \Cerad\Services($config);

  }
  public function setUp()
  {
    $this->services = self::$servicesx;
  }
  public function testTimeStamp()
  {
    $ts = $this->services->getTimeStamp();
    $this->assertEquals(14,strlen($ts));
  }
  public function testEm()
  {
    $em = $this->services->em;
    
    $id = 3;
    $openid = $em->find('OSSO\Openid',$id);
    $this->assertEquals('Arthur Hundiak',$openid->getDisplayName());
    $this->assertEquals('ArthurHundiak', $openid->getUserName());
  }
  public function testRequest()
  {
    $request = $this->services->request;

    $this->assertEquals('Cerad\Request',get_class($request));
    
  }
}
?>
