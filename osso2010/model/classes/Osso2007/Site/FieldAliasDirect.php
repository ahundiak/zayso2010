<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Osso2007_Site_FieldAliasDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.field_alias';
  protected $ignoreDupKey = true;

  protected $fieldAliases = array
  (
    // NEMC
    'SJ-U6-1'  => 'SJ U06-1',
    'SJ-U6-2'  => 'SJ U06-2',
    'SJ-U8-1'  => 'SJ U08-1',
    'SJ-U8-2'  => 'SJ U08-2',
    'SJ U14'   => 'SJ U14-1',
    'SJ-U12 1' => 'SJ U12-1',
    'SJ U10 2N'=> 'SJ U10-2N',
    'SJ U10 1S'=> 'SJ U10-1S',

    // Madison 2010
      /*
    'Dublin 1'           => 'Malcolm',
    'Dublin 2'           => 'Dublin #2',
    'Dublin 3'           => 'Dublin #3',
    'Dublin 4'           => 'Dublin #4',
    'Dublin 5'           => 'Dublin #5',
    'Dublin 6'           => 'Dublin #6',
    'Dublin 7'           => 'Dublin #7',
    'Dublin 8'           => 'Dublin #8',
    'Dublin 9'           => 'Dublin #9', */
    'Dubin 9'            => 'Dublin #9',
    'P Lower Lower N'    => 'Palmer LL North',
    'P Lower Lower S'    => 'Palmer LL South',
    'P Lower Lower C'    => 'Palmer LL Center',

    // Huntsville
    'International'      => 'Palmer Intl',
    'JH3'                => 'John Hunt 3',
    'JH4'                => 'John Hunt 4',
    'JH5'                => 'John Hunt 5',
    'JH6'                => 'John Hunt 6',
    'JH7'                => 'John Hunt 7',
    'JH8'                => 'John Hunt 8',

    'JH15'               => 'John Hunt 15',
    'JH16'               => 'John Hunt 16',
    'JH17'               => 'John Hunt 17',
    'JH18'               => 'John Hunt 18',
    'JH19'               => 'John Hunt 19',
    'JH20'               => 'John Hunt 20',

    'IP1'                => 'Ice Plex 1',
    'IP2'                => 'Ice Plex 2',

    // Monrovis
    'MMS'                => 'Monrovia MS',

    'CS ES U14'          => 'Creekside ES U14',
    'CS ES U12'          => 'Creekside ES U12',
    'CS ES U10'          => 'Creekside ES U10',
    'SL-E U12'           => 'SL-E',
      
    'Harvest Elementary' => 'Harvest',
    'Westminster East'   => 'Westmin U10 East',
    'WCA U10'            => 'Westmin U10 East',
    'WCA U12'            => 'Westmin U12 West',
    'Madison'            => 'Madison TBD',
    'Lincoln'            => 'South Lincoln',
    'Lincoln CO'         => 'South Lincoln',
    'Dublin 1.'          => 'Dublin #1',
    'Dublin 5.'          => 'Dublin #5',
    'NE Madison CO'      => 'HG Park U10-1',
    'SJU10-1'            => 'SJ U10-1',
    'South Lincoln / D'  => 'Davidson D',
    'Camp Hellen'        => 'Camp Helen',
    'Camp Hellen U10'    => 'Camp Helen U10',
    'HGHS'               => 'Hazel Green HS',
    'Hazelgreen High'    => 'Hazel Green HS',
    'Monrovia Middle School'  => 'Monrovia Middle',
    'Athens Sportsplex Fld 3' => 'Athens 3',
    'Athens Sportsplex Fld 4' => 'Athens 4',
    'Cullman 1'          => 'Cullman #1',
  );
  public function processAlias($alias)
  {
    if (is_array($alias)) $alias = $alias['alias'];

    if (isset($this->fieldAliases[$alias])) return $this->fieldAliases[$alias];
    
    return $alias;
  }
}
?>
