<?php
class Osso_User
{
  protected $context;
  protected $data;
  protected $db;

  public function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->db = $this->context->dbOsso;

    $session = $this->context->session;

    $data = $session->get('user');

    if (!$data) $data = $this->genGuestData();

    $this->data = $data;
  }
  public function save()
  {
    $session = $this->context->session;
    $session->set('user',$this->data);
  }
  public function changeUser($id,$isLoggedIn = false)
  {
    // Check data
    $direct = new Osso_Account_AccountDirect($this->context);
    $results = $direct->getUserData(array('id' => $id));

    if ($results['success'] != true) $this->data = $this->genGuestData();
    else
    {
      $this->data = $results['data'];
      $this->data['isLoggedIn'] = $isLoggedIn;
    }
    $this->save();
  }
  public function logout()
  {
    $this->data['isLoggedIn'] = false; // Maybe check remember me here?
    $this->save();
  }
  protected function genGuestData()
  {
    return array
    (
      'id'         => -1,
      'name'       => 'Guest',
      'isLoggedIn' => false, // Guests are always considered to be logged in?
    );
  }
  public function __get($name)
  {
    // Use cache info
    if (isset($this->data[$name])) return $this->data[$name];

    switch($name)
    {
      case 'name':        return $this->getName($name);    break;
      case 'accountName': return $this->account_user_name; break;

      case 'isGuest':
        if($this->id > 0) return false;
        return true;
        break;
    }
    return NULL;
  }
  protected function getName($key)
  {
    $name = NULL;
    $lname = $this->lname;
    $fname = $this->fname;
    if ($fname) $name = $fname;
    if ($lname) {
      if ($name) $name .= ' ' . $lname;
      else       $name  =       $lname;
    }
    $this->data['name'] = $name;
    return $name;
  }
}

?>
