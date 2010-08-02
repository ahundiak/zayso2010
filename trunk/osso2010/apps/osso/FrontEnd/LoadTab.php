<?php
class FrontEnd_LoadTab
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
    'home'           => 'Osso_Home_HomeTab',
    'welcome'        => 'Osso_Welcome_WelcomeTab',
    'training'       => 'Osso_Training_TrainingTab',
    'account-create' => 'Osso_Account_Create_AccountCreateTab',
  );

  function execute($args)
  {
    if ((!is_array($args) || count($args) < 1))
    {
      die("LoadTab executed with no tab name\n");
    }
    $arg = array_shift($args);
    if (!isset($this->argClassNames[$arg]))
    {
      die("Invalid LoadTab arg: $arg\n");
    }
    $argClassName = $this->argClassNames[$arg];

    $item = new $argClassName($this->context);

    $html = $item->execute($args);

    header('Content-type: text/html');
    echo $html;
  }
}
?>
