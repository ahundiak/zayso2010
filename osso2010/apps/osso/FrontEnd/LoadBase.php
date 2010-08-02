<?php
class FrontEnd_LoadBase
{
  protected $context;
  
  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init() {}

  protected $argClassNames = array
  (
    'welcome'        => 'Tab_Welcome_WelcomeTab',
    'account-create' => 'Tab_Account_Create_AccountCreateTab',
  );
}
?>
