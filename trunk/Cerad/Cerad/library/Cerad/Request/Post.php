<?php
class Cerad_Request_Post extends Cerad_Request_Base
{
  protected function init()
  {
    if (!$this->data) $this->data = $_POST;
  }
}
?>
