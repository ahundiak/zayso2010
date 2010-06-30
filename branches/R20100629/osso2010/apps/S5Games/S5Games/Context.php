<?php
class S5Games_Context extends Cerad_Context
{
  protected $dbAlias = 'dbS5Games';
  protected $sessionCookieName = 'S5Games';

  protected function init()
  {
    return parent::init();
  }
  function getSessionData()
  {
    if (isset($_COOKIE[$this->sessionCookieName]))
    {
      $data = json_decode($_COOKIE[$this->sessionCookieName],TRUE);
      return $data;
    }

    // By default, setup the guest user
    $userInfo = $this->getUserDefaultInfo();
    $data = array
    (
      'member_id'   => $userInfo['id'],
      'member_name' => $userInfo['name'],
    );
    return $data;
  }
  function setSessionData($data)
  {
    $value = json_encode($data);

    setcookie($this->sessionCookieName,$value,mktime(0, 0, 0, 12, 31, 2015));

    /*
    $expires = date(DATE_RFC822,mktime(0, 0, 0, 12, 31, 2020));

    $response = $this->context->getResponse();
    $response->setHeader
    (
      'Set-cookie',
      "zayso2009={$sessionId}; expires={$expires}"
    );
     */
  }
  function getUsersPredefined() { return $this->params['users']; }
  function getUserInfo($id)
  {
    $users = $this->getUsersPredefined();
    foreach($users as $user)
    {
      if ($user['id'] == $id) return $user;
    }
    return array
    (
      'id'   => $id,
      'name' => 'Unknown',
      'pass' => 'unknown',
    );
  }
  function getUserDefaultId() { return $this->params['userDefaultId']; }
  function getUserDefaultInfo()
  {
    return $this->getUserInfo($this->getUserDefaultId());
  }
}
?>