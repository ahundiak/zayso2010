<?php
namespace Area5CFApp\Admin\Import;

class ImportView extends \Area5CFApp\base\View
{
  protected $tplTitle   = 'Area5CF Import';
  protected $tplContent = 'Area5CFApp/Admin/Import/ImportTpl.html.php';

  public function process($data)
  {
    $this->tplData = $data;
    $this->renderPage();
  }
}
?>
