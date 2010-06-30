<?php
class EventModelTest extends BaseModelTest
{
    /* ------------------------------------------
     * Event
     */
    public function testEventFind()
    {
        $model = $this->context->models->EventModel;
        
        $id = 3000;
        $item = $model->find($id);
        $this->assertEquals($item->id, $id);
    }
    public function testEventSearch()
    {
        $model = $this->context->models->EventModel;

        $search = new SearchData();
        $search->unitId         = 1;
        $search->yearId         = 6;
        $search->eventTypeId    = 1;
        $search->seasonTypeId   = 1;
        $search->scheduleTypeId = 1;
        
        $search->eventId = 3000;
        
        $search->wantx = TRUE;
        
        $items = $model->search($search);
        
        $this->assertEquals(count($items),1);
        $item = $items[3000];
        //Zend::dump($item);
        $this->assertEquals($item->id, 3000);
        
        $this->assertEquals($item->eventTypeDesc,'Game');
        
        $this->assertEquals($item->scheduleTypeKey,'RS');
        
        $this->assertEquals($item->fieldDesc,'Westmin U10 East');
        
        $this->assertEquals($item->fieldSiteId,  1);
        $this->assertEquals($item->fieldSiteDesc,'Westminster');
    }  
    public function testEventSearchOne()
    {
        $model = $this->context->models->EventModel;

        $search = new SearchData();
        $search->eventId = 3265;
        
        $search->wantx = TRUE;
        
        $item = $model->searchOne($search);
        
        //Zend::dump($item);
        $this->assertEquals($item->id, 3265);
        
        $this->assertEquals($item->eventTypeDesc,'Game');
        
        $this->assertEquals($item->scheduleTypeKey,'RS');
        
        $this->assertEquals($item->fieldDesc,'Monrovia Middle');
        
        $this->assertEquals($item->fieldSiteId,  15);
        $this->assertEquals($item->fieldSiteDesc,'Monrovia Middle');
    }  
    public function testEventSearchDate()
    {
        $model = $this->context->models->EventModel;

        $search = new SearchData();
        $search->unitId         = 1;
//      $search->yearId         = 6;
        $search->eventTypeId    = 1;
//      $search->seasonTypeId   = 1;
        $search->scheduleTypeId = 1;
        
        $search->dateGE = '20061001';
        $search->dateLE = '20061009';
        
        $items = $model->search($search);
//      Zend::dump($items);
        $this->assertEquals(count($items),55);
    }
    /* -----------------------------------------------------------------
     * Event Person
     */
    public function testEventPersonFind()
    {
        $model = $this->context->models->EventPersonModel;
        
        $id = 3060;
        $item = $model->find($id);
        $this->assertEquals($item->id,$id);
    }
    public function testEventPersonSearch()
    {
        $model = $this->context->models->EventPersonModel;

        $search = new SearchData();
        $search->eventId    = 3000;
        $search->wantx      = TRUE;
        $search->wantEvent  = TRUE;
        $search->wantPerson = TRUE;
        
        $items = $model->search($search);
        //Zend::dump($items);
        
        $item = $items[3060]; //Zend::dump($item); die();
        $this->assertEquals($item->personTypeKey,  'CR');
        $this->assertEquals($item->personLastName, 'Bowman');
        $this->assertEquals($item->personFirstName,'Dowling');
        
        $this->assertEquals($item->person->id,   591);
        $this->assertEquals($item->person->namex,'Bowman, Dowling');
        
        $this->assertEquals($item->event->date,'20060909');
    }
    public function testEventPersonDistinct()
    {
        $model = $this->context->models->EventPersonModel;
        
        $search = new SearchData();
        $search->personId = 591;
        
        $search->dateGE = '20060901';
        $search->dateLE = '20060910';
        
        $ids = $model->searchDistinct($search);
        //Zend::dump($ids);
        $where = $this->context->db->quoteInto('event_id IN (?)',$ids);
        
        $this->assertEquals($where,"event_id IN ('3000','3075')");
    }
    /* ------------------------------------------
     * Event Team
     */
    public function testEventTeamFind()
    {
        $model = $this->context->models->EventTeamModel;
       
        $id = 6562; // Event 3000
        $item = $model->find($id);
        $this->assertEquals($item->id,$id);
    }
    public function testEventTeamSearch()
    {
        $model = $this->context->models->EventTeamModel;

        $search = new SearchData();
        $search->eventId       = 3000;
        $search->wantx         = TRUE;
        $search->wantEvent     = TRUE;
        $search->wantTeam      = TRUE;
        $search->wantCoachHead = TRUE;
        
        $items = $model->search($search);
//        Zend::dump($items);die();
        
        $team1 = $items[6562]; //Zend::dump($team1);
        $team2 = $items[6563];
        
        $this->assertEquals($team1->unitDesc,'R0894 Monrovia');
        $this->assertEquals($team2->unitDesc,'R0498 Madison' );
        
        $this->assertEquals($team1->eventTeamTypeDesc,'Home');
        $this->assertEquals($team2->eventTeamTypeDesc,'Away');
        
        $this->assertEquals($team1->event->eventTypeDesc,'Game');
        $this->assertEquals($team1->event->eventTypeDesc,'Game');
        
        $this->assertEquals($team1->event->date,'20060909');
        $this->assertEquals($team1->event->date,'20060909');
        
        $this->assertEquals($team1->phyTeam->divisionSeqNum,4);
        $this->assertEquals($team2->phyTeam->divisionSeqNum,3);
        
        $this->assertEquals($team1->coachHead->name,'Kelly Harness');
        $this->assertEquals($team2->coachHead->name,'Terry Parker');
    }
    protected function getEventSearch($home = FALSE, $away = FALSE)
    {
        $models = $this->context->models;
        
        $eventTeamModel     = $this->context->models->EventTeamModel;
        $eventTeamTypeModel = $this->context->models->EventTeamTypeModel;
        
        $divisionModel = $this->context->models->DivisionModel;

        $search = new SearchData();
        $search->dateGE = '20061001';
        $search->dateLE = '20061009';
        $search->unitId = 1;
        $search->divisionId = $divisionModel->getDivisionIdsForAgeRange(13,14,TRUE,TRUE,FALSE);
        
        $search->eventTeamTypeId = $eventTeamTypeModel->getEventTeamTypeIds($home,$away);
        
        return $search;
    }
    public function testEventTeamSearchDistinct()
    {
        $models = $this->context->models;
        
        $eventTeamModel = $this->context->models->EventTeamModel;
        
        $search = $this->getEventSearch(TRUE,FALSE);
        $eventIds = $eventTeamModel->searchDistinct($search);
        $this->assertEquals(count($eventIds),3);
        
        $search = $this->getEventSearch(FALSE,TRUE);
        $eventIds = $eventTeamModel->searchDistinct($search);
        $this->assertEquals(count($eventIds),10);
        
        $search = $this->getEventSearch(TRUE,TRUE);
        $eventIds = $eventTeamModel->searchDistinct($search);
        $this->assertEquals(count($eventIds),10);
        
    }
    public function testEventSearchSchedule()
    {
        $models = $this->context->models;
     
        $eventModel       = $this->context->models->EventModel;   
        $eventTeamModel   = $this->context->models->EventTeamModel;
        $eventPersonModel = $this->context->models->EventPersonModel;
        
        // First get the events for the team criteria
        $search = $this->getEventSearch(TRUE,FALSE);
        $eventIds = $eventTeamModel->searchDistinct($search);
        $this->assertEquals(count($eventIds),3);
        
        // Now the officials
        $search = new SearchData();
        $search->dateGE = '20061001';
        $search->dateLE = '20061009';
        $search->personId = 0;  // Need to test
        $eventIdxs = $eventPersonModel->searchDistinct($search);
        //Zend::dump($eventIdxs);
        
        // Now the big query
        $search = new SearchData();
        $search->eventId = $eventIds;
        $search->wantEventPersons = TRUE;
        
        $events = $eventModel->searchSchedule($search);
        $this->assertEquals(count($events),3);
        
        $event = $events[3249]; // Zend::dump($event);
        $this->assertEquals($event->fieldSiteDesc,'Monrovia Middle');
        $this->assertEquals(count($event->teams),2);
        
        $homeTeam = $event->teams[EventTeamTypeModel::TYPE_HOME];
        $this->assertEquals($homeTeam->id,7060);
        $this->assertEquals($homeTeam->eventTeamTypeId,  EventTeamTypeModel::TYPE_HOME);
        $this->assertEquals($homeTeam->eventTeamTypeDesc,'Home');
        
        $this->assertEquals($homeTeam->coachHead->name,'Matthew Sweet');
        $this->assertEquals($homeTeam->schedDesc,'R0894-U14G-03 Matthew Sweet');
        
        $event = $events[3266]; // Zend::dump($event->persons);
        $this->assertEquals(count($event->persons),2);
        
        $cr = $event->persons[EventPersonTypeModel::TYPE_CR];
        $this->assertEquals($cr->personLastName,'Bowman');
    }
    function testDateTimeField()
    {
        $model = $this->context->models->EventModel;
        $item = $model->searchByDateTimeField('20070825','0830',74);
        $this->assertNotNull($item);
        $this->assertEquals ($item->id,3554);
        
        $homeTeam = $item->teamHome;
        $awayTeam = $item->teamAway;
        $this->assertEquals($homeTeam->schTeamId,1757);
        $this->assertEquals($awayTeam->schTeamId,1756);
        
    }
}
?>
