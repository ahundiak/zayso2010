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

    'JH9'                => 'John Hunt 9',
    'JH9a'               => 'John Hunt 9A',
    'JH9b'               => 'John Hunt 9B',
    'JH9c'               => 'John Hunt 9C',

    'JH12'               => 'John Hunt 12',
    'JH12a'              => 'John Hunt 12A',
    'JH12b'              => 'John Hunt 12B',
    'JH12c'              => 'John Hunt 12C',

    'JH13'               => 'John Hunt 13',
    'JH13a'              => 'John Hunt 13A',
    'JH13b'              => 'John Hunt 13B',
    'JH13c'              => 'John Hunt 13C',
    'JH13d'              => 'John Hunt 13D',

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

    'NW Field'           => 'Endeavor ES NW',
    'NE Field'           => 'Endeavor ES NE',
    'SW Field'           => 'Endeavor ES SW',
    'SE Field'           => 'Endeavor ES SE',

    'CS ES U14'          => 'Creekside ES U14',
    'CS ES U12'          => 'Creekside ES U12',
    'CS ES U10'          => 'Creekside ES U10',
    'SL-E U12'           => 'SL-E',

    // Not being used in 2010
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
