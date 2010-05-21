<?php
class Direct_Schedule extends ExtJS_Direct_Base
{
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
