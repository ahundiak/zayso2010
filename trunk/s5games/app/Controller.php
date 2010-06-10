<?php
class Controller
{
  protected $contextx = NULL;
  protected $contextClassName = 'Context';
  
  function __construct()
  {
    $this->init();
  }
  protected function init() {}

  public function __get($name)
  {
    switch($name)
    {
      case 'context': return $this->getContext();  break;
      case 'user':    return $this->context->user; break;
    }
  }
  protected function getContext()
  {
    if (!$this->contextx)
    {
      $params = require('config.php');
      $contextClassName = $this->contextClassName;
      $this->contextx = new $contextClassName($params);
    }
    return $this->contextx;
  }
  public function getSess($name,$defaultValue = NULL)
  {
    return $this->context->session->get($name,$defaultValue);
    
    if (isset($_SESSION[$name])) return $_SESSION[$name];
    return $defaultValue;
  }
  public function getPost($name,$defaultValue = NULL)
  {
    if (isset($_POST[$name])) return trim($_POST[$name]);
    return $defaultValue;
  }
  public function getGet($name,$defaultValue = NULL)
  {
    if (isset($_GET[$name])) return trim($_GET[$name]);
    return $defaultValue;
  }
  public function hasGet($name)
  {
    if (isset($_GET[$name])) return TRUE;
    return FALSE;
  }
  public function escape($value)
  {
    return htmlspecialchars($value);
  }
  public function formOptions($options, $value = NULL)
  {
    $html = NULL;
    foreach($options as $key => $content)
    {
      if ($key == $value) $select = ' selected="selected"';
      else                $select = NULL;
            
      $html .= 
        '<option value="' . htmlspecialchars($key) . '"' . $select .
        '>' . htmlspecialchars($content) . '</option>' . "\n";
            
    }
    return $html;
  }
  public function formCheckBox($name,$checked = FALSE, $value='1')
  {
    if ($checked) $check = 'checked="checked" ';
    else          $check = '';
        
    $value = $this->escape($value);
        
    return "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" {$check}\>\n";
  }
  public function formatDate($date)
  {
    if (strlen($date) < 8) return $date;
        
    $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
        
    return date('D M d',$stamp);
  }
  public function formatTime($time)
  {
    switch(substr($time,0,2))
    {
      case 'BN': return 'BYE No Game';
      case 'BW': return 'BYE Want Game';
      case 'TB': return 'TBD';   
    }
    $stamp = mktime(substr($time,0,2),substr($time,2,2));
        
    return date('h:i a',$stamp);
  }
  public function execute()
  {
    switch($_SERVER['REQUEST_METHOD'])
    {
      case 'GET':  return $this->executeGet();
      case 'POST': return $this->executePost();
      default: die("Unknown Reguest Method: {$_SERVER['REQUEST_METHOD']}");
    }
  }
  public function processTemplate($name,$tpl = array())
  {
    // Process the template
    ob_start();
    include $name;
    $content = ob_get_clean();
		
    ob_start();
    include 'page.phtml';
    $page = ob_get_clean();

    echo $page;
  }
}
?>