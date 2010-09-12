<?php
class Cerad_Services
{
  protected $context;
  protected $items = array();
  protected $itemsClassNames = array();

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  public function get($name,$cache = true)
  {
    if ($cache)
    {
      if (isset($this->items[$name])) return $this->items[$name];
    }
    if (!isset($this->itemClassNames[$name])) return null;

    $itemClassName = $this->itemClassNames[$name];
    $item = new $itemClassName($this->context);

    if ($cache) $this->items[$name] = $item;

    return $item;
  }
  public function getClassName($name)
  {
    if (!isset($this->itemClassNames[$name])) return null;

    return $this->itemClassNames[$name];
  }
  public function __get($name)
  {
    return $this->get($name);
  }
}
?>
