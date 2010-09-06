<?php
class Osso2007_Index_IndexHomeView extends Osso2007_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Zayso Home Page';

    $this->tplContent = 'Osso2007/Index/IndexHomeTpl.html.php';

    $this->tplLogin   = 'Osso2007/Index/IndexLoginTpl.html.php';
  }
  function process($data)
  {
    $models = $this->context->models;
        
    $this->homeIndexData = $data;
    $this->homeLoginData = $data;

    $this->unitPickList = $models->UnitModel->getPickList();
    $this->agePickList  = $models->Division->getAgePickList();
               
    /* And render it  */      
    $this->context->response->setBody($this->renderPage());
    return;
  }
}
?>
