<?php
class BasicTest extends PHPUnit_Framework_TestCase
{
  // No real need to test this since already using it to get going
  function testLoader()
  {
    $this->assertTrue(true);
  }
  // Make sure debug dumper is okay
  function testDebug()
  {
    ob_start();
    $a = 42;
    Cerad_Debug::dump($a);
    $out = trim(ob_get_clean());
    //echo $out . ' ' . strlen($out);
    $this->assertEquals('int(42)',$out);
  }
}
?>
