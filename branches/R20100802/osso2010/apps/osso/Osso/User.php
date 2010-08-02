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
    $result = $direct->getUserData(array('id' => $id));

    if (!$result->success)
    {
      $this->data = $this->genGuestData();
    }
    else
    {
      $this->data = $result->row;
      $this->data['isLoggedIn'] = $isLoggedIn;
    }
    $this->genInfo();
    $this->genPermissions();
    
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
      'isAdmin'    => false,
      'isReferee'  => false,
      'isCoach'    => false,
      'info1'      => 'Guest',
      'info2'      => 'Welcome to the AYSO Area 5C Scheduling System',
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
  protected function genInfo()
  {
    // Generates info1 and info 2 lines used for banner
    if ($this->isGuest) return;
    
    $info1 = sprintf('%s MY%s %s',$this->org_key,$this->reg_year,$this->name);

    $info2 = NULL;
    $certRepo = new Eayso_Reg_Cert_RegCertRepo($this->context);
    foreach($this->certs AS $cert)
    {
      $certDesc = $certRepo->getDesc($cert['cert_type']);
      if ($info2) $info2 .= ', ' . $certDesc;
      else        $info2  =        $certDesc;
    }
    if (!$info2) $info2 = 'NO Certifications on record';

    $this->data['info1'] = $info1;
    $this->data['info2'] = $info2;
  }
  protected function genPermissions()
  {
    switch($this->person_id)
    {
      case 1:
        $this->data['isAdmin'] = true;
        break;
    }
    $certs = $this->certs;
    if (isset($certs[200])) $this->data['isReferee'] = true;
    if (isset($certs[300])) $this->data['isCoach']   = true;
 }
}

?>
