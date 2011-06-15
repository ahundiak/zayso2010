<?php
namespace S5GamesApp\Account\Listx;

class ListView extends \S5GamesApp\FrontEnd\View
{
  protected $tplTitle   = 'S5Games List Accounts';
  protected $tplContent = 'S5GamesApp/Account/Listx/ListTpl.html.php';

  public function process($data)
  {
    $accountRepo = $this->services->repoAccount;
    $items = $accountRepo->search($data);

    $data->items  = $items;
    $data->search = $data;

    $data->filterPickList = array(
      '0' => 'Show only searched accounts',
      '1' => 'Show all accounts',
      '2' => 'Show all accounts with issues',
    );
    $this->data = $data;

    switch($data->out)
    {
      case 'csv':
        $response = $this->services->response;
        $response->setBody($this->render('S5GamesApp/Account/Listx/ListTpl.csv.php'));
        $response->setFileHeaders('Accounts.csv');
        return;

      case 'excel':
        $response = $this->services->response;
        $response->setBody($this->render('S5GamesApp/Schedule/Show/ListTpl.xml.php'));
        $response->setFileHeaders('Schedule.xml');
        return;

    }
    $this->renderPage();
  }
}
?>
