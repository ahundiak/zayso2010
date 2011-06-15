<?php
namespace S5GamesApp\Account\Listx;

use \Cerad\DataItem as DataItem;

class ListAction extends \S5GamesApp\FrontEnd\Action
{
  public function processGet($args)
  {
    $services = $this->services;
    $session  = $services->session;
    $search   = $session->load('account-list');
    
    if (isset($args[0])) $search->out = $args[0];
    else                 $search->out = 'web';
    
    $view = new ListView($this->services);
    $view->process(clone $search);

    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;

    $session  = $services->session;
    $data = $session->load('account-list');

    // Extract
    $data->uname   = $request->getPostStr('account_search_uname');
    $data->lname   = $request->getPostStr('account_search_lname');
    $data->aysoid  = $request->getPostStr('account_search_aysoid');
    $data->filter  = $request->getPostInt('account_search_filter');

    $session->save($data);

    return $this->redirect('account-list');
  }
}
?>
