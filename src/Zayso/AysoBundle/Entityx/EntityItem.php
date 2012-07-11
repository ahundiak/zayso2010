<?php

namespace Zayso\AysoBundle\Entity;

class EntityItem
{
  /* ===================================================== */
  public function __get($name)
  {
    if ($name[0] == '_') return null;
    $name = '_' . $name;
    return $this->$name;
  }
  public function __set($name,$value)
  {
    if ($name[0] == '_') return;
    $name = '_' . $name;
    $this->$name = $value;
  }
}
?>
