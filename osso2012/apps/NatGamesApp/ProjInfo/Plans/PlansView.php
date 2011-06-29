<?php
namespace NatGamesApp\ProjInfo\Plans;

class PlansView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle   = 'NatGames Plans';
  protected $tplContent = 'NatGamesApp/ProjInfo/Plans/PlansTpl.html.php';

  public function process($data)
  {
    $yesno = array
    (
      'NA'    => 'Select Answer',
      'Yes'   => 'Yes',
      'No'    => 'No',
    );
    $yesnoref = array
    (
      'NA'    => 'Select Answer',
      'Yes'   => 'Yes - Will referee',
      'No'    => 'No - Will not referee',
    );
    $yesnorefx = array
    (
      'NA'    => 'Select Answer',
      'Yes'   => 'Yes - Will referee',
      'Yesx'  => 'Yes - Will referee if my team advances',
      'No'    => 'No - Will not referee',
    );

    $pickLists = array(
      'attend' => array
        (
          'NA'    => 'Select Answer',
          'Yes'   => 'Yes - For sure',
          'Yesx'  => 'Yes - If my team is selected',
          'No'    => 'No',
          'Maybe' => 'Maybe - Not sure yet',
        ),
      'will_referee'   => $yesno,
      'do_assessments' => $yesno,
      'coaching'       => $yesno,
      'other_jobs'     => $yesno,

      'have_player' => array
        (
          'NA'    => 'Select Answer',
          'Yes'   => 'Yes',
          'Yesx'  => 'Yes - I am a player',
          'No'    => 'No',
        ),
      'want_assessment' => array
        (
          'NA'            => 'Select Answer',
          'No'            => 'No',
          'Informal'      => 'Informal',
          'Intermediate'  => 'Intermediate',
          'AdvancedCR'    => 'Advanced CR',
          'AdvancedAR'    => 'Advanced AR',
          'AdvancedCRAR'  => 'Advanced CR and AR',
          'NationalCR'    => 'National CR',
          'NationalAR'    => 'National AR',
          'NationalCRAR'  => 'National CR and AR',
        ),
        'attend_open' => array
        (
          'NA'    => 'Select Answer',
          'Yes'   => 'Yes - Will participate',
          'No'    => 'No - Will not be there',
        ),
        'avail_wed'       => $yesnoref,
        'avail_thu'       => $yesnoref,
        'avail_fri'       => $yesnoref,
        'avail_sat_morn'  => $yesnoref,
        'avail_sat_after' => $yesnoref,
        'avail_sun_morn'  => $yesnorefx,
        'avail_sun_after' => $yesnorefx,

    );
    $this->pickLists = $pickLists;
    
    // Debug::dump($data);
    $this->data = $data;
    
    $this->renderPage();
  }
  public function genSelect($name)
  {
    $html = sprintf('%s<select name="plans[%s]">%s',"\n",$name,"\n");

    if (isset($this->data->plans[$name])) $value = $this->data->plans[$name];
    else                                  $value = null;

    $options = $this->pickLists[$name];
    foreach($options as $key => $desc)
    {
      if ($value == $key) $selected = 'selected="selected"';
      else                $selected = '';
      $option = sprintf('<option value="%s" %s>%s</option>%s',$key,$selected,$desc,"\n");
      $html .= $option;
    }
    $html .= '</select>' . "\n";
    return $html;
  }
  public function genCheckBox($name)
  {
    if (isset($this->data->plans[$name])) $value = $this->data->plans[$name];
    else                                  $value = null;

    if ($value) $checked = 'checked="checked"';
    else        $checked = '';

    $html = sprintf('<input type="checkbox" name=plans[%s]" value="1" %s />',$name,$checked);

    return $html;
  }
  public function genRoomMateName($name)
  {
    if (isset($this->data->plans[$name])) $value = $this->escape($this->data->plans[$name]);
    else                                  $value = null;

    $html = sprintf('<input type="text" name=plans[%s]" size="30" value="%s" />',$name,$value);

    return $html;
  }
}
?>
