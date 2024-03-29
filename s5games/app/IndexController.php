<?php
class IndexController extends Controller
{
  function executeGet()
  {
    $session = $this->context->session;
    $logout  = $this->context->get->get('logout',0);
    if ($logout)
    {
      $session->set('user_is_auth',FALSE);
      $session->set('user_errors', NULL);
      $session->set('ref_aysoid',  NULL);
      $session->set('ref_status',  NULL);
      $session->set('ref_ass',     NULL);
      return header("location: index.php");
    }
    $tpl = new Cerad_Data();
    $tpl->userName   = $session->get('user_name');
    $tpl->userPass   = $session->get('user_pass');
    $tpl->userAysoid = $session->get('user_aysoid');
    $tpl->userErrors = $session->get('user_errors');

    // Backward compatibility
    if ($tpl->userName == 'General') $tpl->userName = $tpl->userAysoid;

    if ($tpl->userErrors) $session->set('user_errors',NULL);
    
    // Process the template
    $this->processTemplate('index.phtml',$tpl);
  }
}
?>