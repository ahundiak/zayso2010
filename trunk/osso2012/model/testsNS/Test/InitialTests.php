<?php

namespace Test;

class InitialTests extends \PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    // echo "Setup before class\n";
  }
  public function setUp()
  {
    // echo "Setup\n";
  }
  function test1()
  {
    $this->assertTrue(true);
  }
  function test2()
  {
    $this->assertTrue(true);
  }
}
?>
