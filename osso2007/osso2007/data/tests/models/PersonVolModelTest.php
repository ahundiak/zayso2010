<?php

class PersonVolModelTest extends BaseModelTest
{
    public function testPersonVolSearch()
    {
        $model = $this->context->models->PersonVolModel;

        $search = new SessionData();
        $search->lname = 'Hundiak';
        $search->yearId = 6;
        $search->wantx  = TRUE;
                
        $personVols = $model->search($search);
        
        $this->assertEquals(count($personVols),2);
        $this->assertTrue (isset($personVols[1]));
        $this->assertFalse(isset($personVols[2]));
        $this->assertTrue (isset($personVols[3]));

        $person1 = $personVols[1];
        $this->assertEquals($person1->id,1);
        $this->assertEquals($person1->fname,'Art');
        $this->assertEquals($person1->name, 'Art Hundiak');
        //$this->assertEquals($person1->vol->id,1359);
        
        $person3 = $personVols[3];
        $this->assertEquals($person3->id,3);
        $this->assertEquals($person3->fname,'Ethan');

        $vols = $person1->vols;
        $this->assertEquals(count($vols),2);
        $this->assertTrue (isset($vols[1359]));
        $this->assertFalse(isset($vols[1360]));
        $this->assertTrue (isset($vols[1361]));

        $vol =  $vols[1361];
        $this->assertEquals($vol->volTypeId,   27);
        $this->assertEquals($vol->volTypeDesc,'Zayso Administrator');
                                  
        $vols = $person3->vols;
        $this->assertEquals(count($vols),2);
        
    }
   public function testPersonVolPickList()
    {
        $model = $this->context->models->PersonVolModel;
        
        $search = new SearchData();
        $search->yearId       = 6;
        $search->volUnitId    = 1;
        $search->seasonTypeId = 1;
        $search->divisionId   = 13;
        $search->volTypeId    = VolTypeModel::TYPE_HEAD_COACH;
        
        $personPickList = $model->getPersonPickList($search);
        
        //Zend::dump($personPickList);
        $this->assertEquals(count($personPickList),5);
    }
}
