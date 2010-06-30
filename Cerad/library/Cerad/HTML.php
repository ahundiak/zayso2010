<?php
class Cerad_HTML
{
  public function escape($value)
  {
    return htmlspecialchars($value);
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
  public function formOptions($options, $value = NULL)
  {
    $html = NULL;
    foreach($options as $key => $content)
    {
      if ($key == $value) $select = ' selected="selected"';
      else                $select = NULL;

      $html .=
        '<option value="' . $this->escape($key) . '"' . $select .
        '>' . $this->escape($content) . '</option>' . "\n";

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
}
?>