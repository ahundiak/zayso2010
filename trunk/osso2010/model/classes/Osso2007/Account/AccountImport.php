<?php
class Osso2007_Account_AccountImport extends Cerad_Import
{
  protected $readerClassName = 'Osso2007_Account_AccountReader';
  protected $regions = array();

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbOsso;
    $this->ts = $this->context->getTimeStamp();

    $this->count->insertedAccount = 0;
    $this->count->insertedAccountPerson = 0;
  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Account: %u, Person: %u",
      $class, $file,
      $count->total,$count->insertedAccount,$count->insertedAccountPerson);
    return $msg;
  }
  public function processRowData($data)
  {
    $db = $this->db;
    
    $accountId = $data['account_id'];

    // Check existing account
    $accountData = $db->find('account','id',$accountId);
    if ($accountData === FALSE)
    {
      if ($data['level'] != 1) die('Account level ' . $accountId);

      $datax = array
      (
        'id'                => $accountId,
        'account_person_id' => $data['account_person_id'],
        'user_name'         => $data['account_name'],
        'user_pass'         => $data['account_pass'],
        'lname'             => $data['lname'],
        'email'             => $data['email'],
      );
      $db->insert('account','id',$datax);
      $this->count->insertedAccount++;
    }
    // Check existing account_person
    $accountPersonId = $data['account_person_id'];
    $accountPersonData = $db->find('account_person','id',$accountPersonId);
    if ($accountPersonData === FALSE)
    {
      $datax = array
      (
        'id'         => $accountPersonId,
        'account_id' => $accountId,
        'person_id'  => (int)$data['person_id'],
        'org_id'     => (int)$data['org_id'],
        'lname'      =>      $data['lname'],
        'fname'      =>      $data['fname'],
      );
      $db->insert('account_person','id',$datax);
      $this->count->insertedAccountPerson++;
    }
  }
}
?>
