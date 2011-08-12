<?php
namespace Area5CFApp\Account\Create;

class CreateView extends \Area5CFApp\base\View
{
  protected $tplTitle   = 'Area5CF Create Account';
  protected $tplContent = 'Area5CFApp/Account/Create/CreateTpl.html.php';

  public function process($data)
  {
    $tplData = $this->services->dataItem;
    $tplData->accountCreateData = $data;

    // Ref Pick List
    $tplData->refBadgePickList = array(
      'None'         => 'None',
      'Regional'     => 'Regional',
      'Intermediate' => 'Intermediate',
      'Advanced'     => 'Advanced',
      'National'     => 'National',
      'National 2'   => 'National 2',
      'Assistant'    => 'Assistant',
      'U8 Official'  => 'U8',
    );
    $this->tplData = $tplData;
    $this->renderPage();
  }
}
?>
