<?php
class Osso2007_Account_Home_AccountHomeView extends Osso2007_View
{
  function init()
  {
    parent::init();
        
    $this->tplTitle   = 'Account Home';
    $this->tplContent = 'Osso2007/Account/Home/AccountHomeTpl.html.php';
  }
  function process($data)
  {
    $models = $this->context->models;
        
    $this->accountHomeData = $data;
        
    $this->unitPickList = $models->UnitModel->getPickList();
    $this->agePickList  = $models->Division->getAgePickList();
        
    $this->account = $this->context->user->account;

    $directAccount = new Osso2007_Account_AccountDirect($this->context);
    $result = $directAccount->getCerts(array('account_id' => $this->account->id));

    $this->membersx = $result->rows;
    
    // Cerad_Debug::dump($this->membersx); die(0);
        
    /* And render it  */      
    $this->context->response->setBody($this->renderPage());
    return;
  }
}
?>
