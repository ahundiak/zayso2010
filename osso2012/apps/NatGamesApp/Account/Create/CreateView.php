<?php
namespace NatGamesApp\Account\Create;

class CreateView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle   = 'NatGames Create Account';
  protected $tplContent = 'NatGamesApp/Account/Create/CreateTpl.html.php';

  public function process($data)
  {
    $accountData = $data;

    $tplData = new \NatGames\DataItem();
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
