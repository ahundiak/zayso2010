<?php
namespace Area5CFApp\Admin\Import;

class ImportAction extends \Area5CFApp\base\Action
{
  public function processGet($args)
  {
    // $user = $this->services->user;
    
    $data = $this->services->dataItem;

    $view = new ImportView($this->services);
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    $services = $this->services;
    $request  = $services->request;
    $session  = $services->session;

    $data = $session->load('admin-import');

    $impFileName = $_FILES['import_file']['name'];
    $tmpFileName = $_FILES['import_file']['tmp_name'];
    
    if (!$tmpFileName) return $this->redirect('admin-import');


    return $this->redirect('admin-import');
  }
}
?>
