<?php
class Osso2007_Account_Openid_OpenidLinkView extends Osso2007_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Account Openid Link';
    $this->tplContent = 'Osso2007/Account/Openid/OpenidLinkTpl.html.php';
  }
  function process($data)
  {
    $memberId = $this->context->user->member->id;
    $direct = new Osso2007_Account_Openid_OpenidDirect($this->context);
    $result = $direct->fetchRows(array('member_id' => $memberId));

    $this->openids = $result->rows;
    
    /* And render it  */      
    $this->context->response->setBody($this->renderPage());
    return;
  }
}
?>
