<?php
class Osso2007_Event_EventTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso2007_Event_EventDirect';

  public function test_getDistinctIds()
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
