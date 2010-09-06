<?php
class Osso2007_Index_IndexHomeAction extends Osso2007_Action
{
  public function processGet($args)
  {             
    /* Allows coming in with a unit id in the url */
    if (isset($args[0])) $unitId = (int)$args[0];
    else                 $unitId = 0;

    /* Otherwise go with the user defaults */
    if ($unitId <= 0) $unitId = $this->context->user->unitId;
        
    $data = new SessionData();
    $data->unitId = $unitId;
    $data->ageId  = 12;
        
    $view = new Osso2007_Index_IndexHomeView($this->context);
    $view->process(clone $data);
  }
}
?>
