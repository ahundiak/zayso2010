<?php
class VolModelTest extends BaseModelTest
{
    function testVolType()
    {
        $model = $this->context->models->VolTypeModel;
        
        $item = $model->find(VolTypeModel::TYPE_HEAD_COACH);
        $this->assertEquals($item->desc,'Head Coach');
        
        $pickList = $model->getPickList();
        //Zend::dump($pickList);
        
        $desc = $model->getDesc(VolTypeModel::TYPE_HEAD_COACH);
        $this->assertEquals($desc,'Head Coach');  
    }
    function testVolFind()
    {
        $model = $this->context->models->VolModel;
        
        $item = $model->find(1334);
        
        $this->assertEquals($item->unitId,  '1');
        $this->assertEquals($item->personId,'6');
    }
    function testVolSearch()
    {
        $model = $this->context->models->VolModel;

        $search = new SearchData();
        $search->personId = 6;
        $search->wantx = TRUE;
        
        $items = $model->search($search);  //Zend::dump($items);
        $this->assertEquals(count($items),13);
        
        $item = $items[1334];
        $this->assertEquals($item->personId,6);
        $this->assertEquals($item->year, 2006);
        
        $this->assertEquals($item->unitDesc,      'R0894 Monrovia');
        $this->assertEquals($item->volTypeDesc,   'Head Coach');
        $this->assertEquals($item->divisionDesc,  'U14B');
        $this->assertEquals($item->seasonTypeDesc,'Fall');
    }    
    function testVolPickList()
    {
        $model = $this->context->models->VolModel;

        $search = new SearchData();
        $search->unitId = 1;
        $search->yearId = 6;
        $search->divisionId   = 13;
        $search->seasonTypeId = 1;
        $search->volTypeId    = VolTypeModel::TYPE_HEAD_COACH;
        
        $pickList = $model->getPersonPickList($search); //Zend::dump($pickList);
        $this->assertEquals(count($pickList),5);
        $this->assertEquals($pickList[6],'Tipton, Ernie');
    }
 }
?>
