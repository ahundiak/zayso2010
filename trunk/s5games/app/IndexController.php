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
      return header("location: index.php");
    }
    $tpl = new Cerad_Data();
    $tpl->userAysoid = $session->get('user_aysoid');
    $tpl->userPass   = $session->get('user_pass');
    
    // Process the template
    $this->processTemplate('index.phtml',$tpl);
  }
}
?>