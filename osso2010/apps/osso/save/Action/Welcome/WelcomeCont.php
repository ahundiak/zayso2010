<?php
class Action_Welcome_WelcomeCont extends Action_Base_BaseCont
{
  protected $tplTitle = 'OSSO Welcome';
  protected $tplName  = 'Action/Welcome/Welcome.html.php';
  protected $tplPage  = 'Action/Master/Page.html.php';

  function execute($args)
  {
    $session = $this->context->session;
    if ($session->getSessionId()) $session->set('account-login',NULL);

    $direct  = new Osso_Org_OrgDirect($this->context);
    $results = $direct->getOrgGroupOrgPicklist(array('id' => 1));
    $this->orgPickList = $results['records'];
    
    return $this->renderPage();
  }
}

?>
