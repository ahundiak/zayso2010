<?php
class EventPersonTest extends BaseTest
{
	function testEventPersonTypeRead()
	{
		$action = new Direct_Event_EventPersonTypeAction($this->context);
		
		$results = $action->read(array());
		
		$records = $results['records'];
		
		$this->assertEquals(count($records),7);
	}
	function getEventIds()
	{
    $query = new Event_Schedule_EventDistinctQuery($this->context);
    
    $params = array
    (
      'date1' => '20091031',
      'date2' => '20091101',
    );
    
    $ids = $query->execute($params);
		
    return $ids;
	}
	function tessEventSchedDistinctQuery()
	{
		
		$ids = $this->getEventIds($params);
		
		$this->assertEquals(count($ids),85);
		
	}
	function tessEventSchedQueryQuery()
	{
    $ids = $this->getEventIds();
    
    $query = new Event_Schedule_EventQuery($this->context);
    
    $events = $query->process($ids,TRUE);
    
    $this->assertTrue(isset($events[7758]));
    $event = $events[7758];
    
    $this->assertEquals($event->id,   7758);
    $this->assertEquals($event->date,'20091101');
    $this->assertEquals($event->time,'1530');
    
    $this->assertEquals($event->fieldId,79);
    $this->assertEquals($event->fieldDesc,'Ice Plex West');
    
    $team = $event->homeTeam;  // Cerad_Debug::dump($team);
    $this->assertEquals($team->id,16103);
    $this->assertEquals($team->leagueKey,'R0160');
    $this->assertEquals($team->divKey,   'U12C');
    $this->assertEquals($team->divSeqNum, 2);
    
    $coach = $team->coach;
    $this->assertEquals($coach->name,'Jim Meehan');
    
    $team = $event->awayTeam; // Cerad_Debug::dump($team);
    $this->assertEquals($team->id,16104);
    
    $referee = $event->getRefereeByType(Event_EventPersonTypeRepo::TYPE_CR);
    $this->assertEquals($referee->name,'Bob Becher');
    
    //Cerad_Debug::dump($event);
	}
}
?>