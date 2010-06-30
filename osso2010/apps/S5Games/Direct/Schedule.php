<?php
class Direct_Schedule extends ExtJS_Direct_Base
{
  function load($params)
  {
    // Cerad_Debug::dump($params);
    $data = array
    (
      'days' => array
      (
        'day-fri' => false,
        'day-sat' => true,
        'day-sun' => false,
      ),
      'sites' => array
      (
        'site-john_hunt' => false,
        'site-merrimack' => true,
      ),
      'divs' => array
      (
        'div-u10b' => false,
        'duv-u10g' => true,
        'div-u12b' => false,
        'duv-u12g' => true,
        'div-u14b' => false,
        'duv-u14g' => true,
        'div-u16b' => false,
        'duv-u16g' => true,
        'div-u19b' => false,
        'duv-u19g' => true,
      ),
      'sort_by'  => 1,
      'coaches'  => 'The Coach',
      'referees' => '',
      'brackets' => '',
    );
    $results = array
    (
      'success' => true,
      'data'    => $data,
    );
    return $results;
    return $this->wrapResults($data);
  }
  function read($params)
  {
    // Cerad_Debug::dump($params);
    
    $records = array
    (
      array
      (
        'game_id'   => 1000,
        'game_num'  => 1000,
        'date'      => 'Fri',
        'time'      => '13:00 PM',
        'field'     => 'JH  3',
        'div'       => 'U10B',
        'team_home' => 'Home Boys',
        'team_away' => 'Visitors',
      ),
      array
      (
        'game_id'   => 1001,
        'game_num'  => 1001,
        'date'      => 'Sat',
        'time'      => '13:00 PM',
        'field'     => 'JH  4',
        'div'       => 'U10B',
        'team_home' => 'Home Boys',
        'team_away' => 'Visitors',
      ),
    );
    return $this->wrapResults($records);
  }
}

?>
