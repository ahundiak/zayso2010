<?php
class Osso2007_Event_EventTests extends Cerad_Tests_Base
{
  protected $directClassName = 'Osso2007_Event_EventDirect';

  public function test_getRowsForProjectNum()
  {
    $repoEvent = $this->context->repos->event;
    
    $pid = 28;
    $num = 1320;

    $rows = $repoEvent->getRowsForProjectNum($pid,$num);

    $this->assertEquals(1,count($rows));
    $row = array_shift($rows);
    $this->assertEquals(2,count($row['teams']));
  }
  public function sest_getDistinctIds()
  {
    $direct = $this->direct;

    $search = array
    (
      'date_ge' => '20100820',
      'date_le' => '20100827',
      'unit_id' => array(1,4),
      'division_id' => array(16,17,18,19,20,21)
    );

    $result = $direct->getDistinctIds($search);

    $ids = $result->rows;
    
    $this->assertTrue  (count($ids) > 10);
    $this->assertEquals(count($ids),12);

    $search = array
    (
      'event_id' => $ids,
    );
    $result = $direct->getSchedule($search);
    $events = $result->records;

    $result = $direct->getPersons($search);
    $events = $result->records;

    //Cerad_Debug::dump($events);
    return;
  }
}
?>
