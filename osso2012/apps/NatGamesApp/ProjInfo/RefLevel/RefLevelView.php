<?php
namespace NatGamesApp\ProjInfo\RefLevel;

use \Cerad\Debug as Debug;

class RefLevelView extends \NatGamesApp\FrontEnd\View
{
  protected $tplTitle   = 'NatGames Referee Level';
  protected $tplContent = 'NatGamesApp/ProjInfo/RefLevel/RefLevelTpl.html.php';

  public function process($data)
  {
    $data->levels = array
    (
      array('desc' => 'Regular Pool Play', 'cat' => 'pp'),
      array('desc' => 'Play Offs',         'cat' => 'po'),
      array('desc' => 'Jamboree',          'cat' => 'ja'),
      array('desc' => 'EXTRA',             'cat' => 'ex'),
    );
    
    // Debug::dump($data);
    $this->data = $data;
    
    $this->renderPage();
  }
  public function genCheckBox($cat,$name)
  {
    $html = '<td align="center"><input type="checkbox" value="1" name="';

    $html .= 'ref_levels' . '[' . $cat . ']' . '[' . $name . ']" ' ;

    // See if checked
    $refLevels = $this->data->refLevels;
    if (isset($refLevels[$cat][$name]))
    {
      $html .= ' checked="checked" ';
    }
    $html .= '/></td>' . "\n";

    // Bit of a hack for extras
    if (($cat == 'ex') && (substr($name,0,3) == 'U10'))
    {
      $html = '<td>&nbsp;</td>' . "\n";
    }
    return $html;
  }
}
?>
