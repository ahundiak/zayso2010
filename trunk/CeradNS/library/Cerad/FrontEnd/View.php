<?php

namespace Cerad\FrontEnd;

class View
{
  protected $tplPage    = '';
  protected $tplTitle   = 'Base View';
  
  protected $tplContent = '';
    
  protected $tplRedirectDelay = 0;
  protected $tplRedirectLink  = NULL;

  protected $services;
  protected $format;

  public function __construct($services = null)
  {
    $this->services = $services;
    $this->init();
  }
  protected function init()
  {
    // $this->format = new Cerad_Format_HTML($this->context);
  }

  protected function render($tplName)
  { 
    // All I really should need is an include surrounded by some buffer saving
    ob_start();
    include $tplName;
    return ob_get_clean();
  }
  /* -------------------------------------
   * Renders the content
   * Renders the master page with content
   * Puts the result in the response obeject
   */
  protected function renderPage()
  {
    $this->content = $this->render($this->tplContent);

    $this->services->response->setBody($this->render($this->tplPage));

    return;
  }
  protected function renderPageToString()
  {
    $this->content = $this->render($this->tplContent);

    return $this->render($this->tplPage);
  }
  /* This stuff really should be replaced with html object */
  public function formatDate($date)
  {
    return $this->format->date($date);
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
  protected function formOptions($options, $value = null) { return $this->context->html->formOptions($options,$value); }

  public function formOptionsx($options, $value = NULL)
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
  public function formRadioBox($name,$checked = FALSE, $value='1')
  {
    if ($checked) $check = 'checked="checked" ';
    else          $check = '';

    $value = $this->escape($value);
        
    return "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\" {$check}\>\n";
  }
  function formUDC($name,$id)
  {
    if ($id)
    {
      $disable = '';
      $createValue = 'Clone';
    }
    else
    {
      $disable = 'disabled="disabled" ';
      $createValue = 'Create';
    }    
    $html = NULL;
    $html .= "<input type=\"checkbox\" name=\"{$name}_confirm_delete\" value=\"1\"      {$disable}/>\n";
    $html .= "<input type=\"submit\"   name=\"{$name}_submit_delete\"  value=\"Delete\" {$disable}/>\n";
    $html .= "<input type=\"submit\"   name=\"{$name}_submit_create\"  value=\"{$createValue}\" />\n";
    $html .= "<input type=\"submit\"   name=\"{$name}_submit_update\"  value=\"Update\" {$disable}/>\n";

    return $html;
  }
  /* Content is assumed to be already escaped usually by display items */
  public function href($content,$routeName,$par1 = NULL,$par2 = NULL)
  {
    $link = $this->context->url->link($routeName,$par1,$par2);
        
    return "<a href=\"{$link}\">{$content}</a>";
  }
  public function link($routeName = NULL,$par1 = NULL,$par2 = NULL )
  {
    return $this->context->url->link($routeName,$par1,$par2);
  }
  public function file($path)
  {
    return $this->context->url->file($path);
  }
  public function escape($content) { return $this->format->escape($content); }
}
?>
