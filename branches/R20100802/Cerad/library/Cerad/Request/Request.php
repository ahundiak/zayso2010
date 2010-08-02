<?php
class Cerad_Request_Request extends Cerad_Request_Base
{
  protected function init()
  {
    if (!$this->data) $this->data = $_REQUEST;

    // Supports name1/value1/name2/value2
    $this->merge();
  }
}
?>
