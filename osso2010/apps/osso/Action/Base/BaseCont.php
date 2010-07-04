<?php
class Action_Base_BaseCont
{
  protected $context;

  protected $tplTitle = 'OSSO - Base Controller';
  protected $tplPage  = 'Action/Master/Page.html.php';
  protected $tplName  = '';

  protected $tplContent;

  protected $userMustBeLoggedIn = true;
  protected $webDir;

  function __construct($context)
  {
    $this->context = $context;
    $this->init();
  }
  protected function init()
  {
    $this->webDir = dirname($_SERVER['SCRIPT_NAME']);
  }
  public function genUrl($action = null,$params = null)
  {
    $url = $this->webDir;
    if ($action) $url .= '/la/' . $action;

    if ($params) 
    {
      foreach($params as $name => $value)
      {
        $url .= '/' . $name . '/' . $value;
      }
    }

    return $url;
  }
  public function redirect($action = null,$params = null)
  {
    $url = $this->genUrl($action,$params);
    header("Location: $url");
  }
  public function render($name)
  {
    ob_start();
    include $name;
    return ob_get_clean();
  }
  public function renderPage($name = null)
  {
    if (!$name) $name = $this->tplName;

    $this->tplContent = $this->render($name);

    $html = $this->render($this->tplPage);

    header('Content-type: text/html');
    echo $html;
  }
  function executeGet()  {}
  function executePost() {}

  function execute()
  {
    // Filter most pages based on being logged in
    if ($this->userMustBeLoggedIn)
    {
      $user = $this->context->user;
      if (!$user->isLoggedIn)
      {
        $welcome = new Action_Welcome_WelcomeCont($this->context);
        return $welcome->execute();
      }
    }
    
    // Direct based on actions
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') return $this->executePost();
    else                   return $this->executeGet();
  }
}

?>
