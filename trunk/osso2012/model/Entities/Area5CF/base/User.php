<?php
/* =============================================================
 * Always a fun class, mostly database oriented but also has some application
 * specific stuff so like services, use two
 */
namespace Area5CF\base;

class User
{
  protected $services;

  public function __construct($services)
  {
    $this->services = $services;
    $this->init();
  }
  protected function init() {}

  public function getName()
  {
    return 'My Name';
  }
  public function isSignedIn()
  {
    return false;
  }
}
?>
