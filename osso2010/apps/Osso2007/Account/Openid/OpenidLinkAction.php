<?php
class Osso2007_Account_Openid_OpenidLinkAction extends Osso2007_Action
{
  public function processGet($args)
  {
    $view = new Osso2007_Account_Openid_OpenidLinkView($this->context);
    $data = array();
    $view->process($data);
    return;

  }
  public function processPost($args)
  {
    $request  = $this->context->request;
    $redirect = 'account_openid_link';

    $delete = $request->getPost('openid_submit_delete');
    if (!$delete) return $this->redirect($redirect);

    $confirm = $request->getPost('openid_confirm_delete');
    if (!$confirm) return $this->redirect($redirect);

    $ids = $request->getPost('openid_delete_ids');
    if ((!$ids) || (count($ids) < 1)) return $this->redirect($redirect);

    $direct = new Osso2007_Account_Openid_OpenidDirect($this->context);
    $direct->delete($ids);

    return $this->redirect($redirect);
  }
}
?>
