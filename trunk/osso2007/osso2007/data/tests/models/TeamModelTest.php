<?php
class TeamModelTest extends BaseModelTest
{
    public function testPhyTeamFind()
    {
        $model = $this->context->models->PhyTeamModel;
        
        $item = $model->find(927);
        $this->assertEquals($item->id, 927);
        
        $item = $model->find(9999);
        $this->assertEquals($item->id,NULL);
    }
    public function testSchTeamFind()
    {
        $model = $this->context->models->SchTeamModel;
        
        $item = $model->find(1253);
        $this->assertEquals($item->id, 1253);
        $this->assertEquals($item->phyTeamId, 927);

    }
    public function testPhyTeamSearch()
    {
        $model = $this->context->models->PhyTeamModel;

        $search = new SearchData();
        $search->unitId       = 1;
        $search->yearId       = 6;
        $search->seasonTypeId = 1;
        $search->divisionId   = 13; // U14B
        $search->wantx        = TRUE;
        
        $search->wantCoachHead = TRUE;
        
        $teams = $model->search($search);
        // Zend::dump($teams);        
        
        $this->assertEquals(count($teams),4);

        $team = $teams[927];
        
        $this->assertEquals($team->key,'R0894-U14B-03');
        
        $coachHead = $team->coachHead;
        $this->assertEquals($coachHead->lname,'Dorsett');
    }
    public function testSchTeamSearch()
    {
        $model = $this->context->models->SchTeamModel;

        $search = new SearchData();
        $search->unitId         = 1;
        $search->yearId         = 6;
        $search->seasonTypeId   = 1;
        $search->scheduleTypeId = 1;
        $search->divisionId     = 13; // U14B
        $search->wantx          = TRUE;
        
        $search->wantPhyTeam   = TRUE;
        $search->wantCoachHead = TRUE;
        
        $items = $model->search($search);
        //Zend::dump($items);        
        
        $this->assertEquals(count($items),4);
        
        $schTeam = $items[1254];
        $this->assertEquals($schTeam->phyTeamId,928);
        
        $phyTeam = $schTeam->phyTeam;
        $this->assertEquals($phyTeam->id,928);
        
        $coachHead = $phyTeam->coachHead;
        $this->assertEquals($coachHead->name,'Jeff Newman');
        
    }
    public function testPhyTeamHighestSeqNum()
    {
        $model = $this->context->models->PhyTeamModel;

        $search = new SearchData();
        $search->unitId       = 1;
        $search->yearId       = 6;
        $search->seasonTypeId = 1;
        $search->divisionId   = 14; // U14G

        $seqn = $model->getHighestSeqNum($search);
        
        $this->assertEquals($seqn,3);
    }
    public function sestPhyTeamPersonFind()
    {
        $model = $this->context->models->PhyTeamPersonModel;
        
        $item = $model->find(806);
        $this->assertEquals($item->id,       806);
        $this->assertEquals($item->phyTeamId,927);
        $this->assertEquals($item->personId, 705);
    }
    public function testPhyTeamPersonSearch()
    {
        $model = $this->context->models->PhyTeamPersonModel;

        $search = new SearchData();
        $search->phyTeamId = 927;
        $search->wantx = TRUE;
        
        $items = $model->search($search);
        //Zend::dump($items);
        $this->assertEquals(count($items),1);
        
        $item = $items[806];
        $this->assertEquals($item->personId,705);
        $this->assertEquals($item->volTypeDesc,'Head Coach');
    }           
    public function testPhyTeamPickList()
    {
        $model = $this->context->models->PhyTeamModel;

        $search = new SearchData();
        $search->unitId       = 1;
        $search->yearId       = 6;
        $search->seasonTypeId = 1;
        $search->divisionId   = 14; // U14G

        $pickList = $model->getPickList($search);
        // Zend::dump($pickList);
        $this->assertEquals(count($pickList),3);
    }
}
