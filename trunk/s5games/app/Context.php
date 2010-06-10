<?php
class RequestSession
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = NULL;
    $this->init();
  }
  protected function init() { return; }

  public function get($name,$default = NULL)
  {
    if(isset($_SESSION[$name])) return $_SESSION[$name];
    return $default;
  }
  public function has($name)
  {
    if(isset($_SESSION[$name])) return true;
    return false;
  }
  public function set($name,$value)
  {
    $_SESSION[$name] = $value;
  }
}
class RequestGet
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = NULL;
    $this->init();
  }
  protected function init() { return; }

  public function get($name,$default = NULL)
  {
    if(isset($_GET[$name])) return $_GET[$name];
    return $default;
  }
  public function has($name)
  {
    if(isset($_GET[$name])) return true;
    return false;
  }
  public function set($name,$value)
  {
    $_GET[$name] = $value;
  }
}
class RequestPost
{
  protected $context = NULL;

  public function __construct($context)
  {
    $this->context = NULL;
    $this->init();
  }
  protected function init() { return; }

  public function get($name,$default = NULL)
  {
    if(isset($_POST[$name])) return $_POST[$name];
    return $default;
  }
  public function has($name)
  {
    if(isset($_POST[$name])) return true;
    return false;
  }
  public function set($name,$value)
  {
    $_POST[$name] = $value;
  }
}
class Context extends Cerad_Context
{
  public function __get($name)
  {
    switch($name)
    {
      case 'get':     return $this->getGet();     break;
      case 'post':    return $this->getPost();    break;
      case 'user':    return $this->getUser();    break;
      case 'session': return $this->getSession(); break;
    }
    return parent::__get($name);
  }
  protected function getUser()
  {
    $user = $this->get('user');
    if ($user) return $user;

    $user = new User($this);

    $isAuth = $this->session->get('user_is_auth');

    if ($isAuth)
    {
      $user->isAuth = true;
      $aysoid = $this->session->get('user_aysoid');
      if ($aysoid) $user->loadEayso($aysoid);
    }

    $this->set('user',$user);
    
    return $user;
  }
  protected function getSession()
  {
    $item = $this->get('session');
    if ($item) return $item;

    $item = new RequestSession($this);

    $this->set('session',$item);

    return $item;
  }
  protected function getGet()
  {
    $item = $this->get('get');
    if ($item) return $item;

    $item = new RequestGet($this);

    $this->set('get',$item);

    return $item;
  }
  protected function getPost()
  {
    $item = $this->get('post');
    if ($item) return $item;

    $item = new RequestPost($this);

    $this->set('post',$item);

    return $item;
  }
}
?>
