<?php
namespace Area5CF\tests;

/* =========================================================
 * Uses global $config
 * Creates one service per test case
 * Maybe should be in the base directory?
 */
class BaseTests extends \PHPUnit_Framework_TestCase
{
  static    $servicesx;
  protected $services;

  public static function setUpBeforeClass()
  {
    $config = $GLOBALS['config'];

    self::$servicesx = new \Area5CF\base\Services($config);

    return;
  }
  public function setUp()
  {
    $this->services = self::$servicesx;    
  }
}
?>
