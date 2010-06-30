<?php
class UnitModelTest extends BaseModelTest
{
    function testUnitType()
    {
        $model = $this->context->models->UnitTypeModel;
        
        $item = $model->find(UnitTypeModel::TYPE_AYSO_REGION);
        $this->assertEquals($item->desc,'AYSO Region');
        
        $pickList = $model->getPickList();
        //Zend::dump($pickList);
        
        $desc = $model->getDesc(UnitTypeModel::TYPE_AYSO_REGION);
        $this->assertEquals($desc,'AYSO Region');  
    }
    function testUnitFind()
    {
        $model = $this->context->models->UnitModel;
        
        $item = $model->find(5); //Zend::dump($item);
        
        $this->assertEquals($item->id,      '5');
        $this->assertEquals($item->descPick,'R0557 Lincoln County');
    }
    function testUnitSearch()
    {
        $model = $this->context->models->UnitModel;

        $search = new SearchData();
        $search->unitId = 5;
        $search->wantx = TRUE;
        
        $item = $model->searchOne($search);  // Zend_Debug::dump($item);die();
        
        $this->assertEquals($item->id,5);
        $this->assertEquals($item->unitTypeDesc,'AYSO Region');
    }    
    function testUnitPickList()
    {
        $model = $this->context->models->UnitModel;
        
        $pickList = $model->getPickList(); //Zend::dump($pickList);
        
        $this->assertEquals($pickList[5],'R0557 Lincoln County');
    }
    function testUnitKeySearch()
    {
        $model = $this->context->models->UnitModel;
        $item = $model->searchByKey('R0498');
        $this->assertNotNull($item);
        $this->assertEquals($item->id,4);
    }        
    function testUnitNumberSearch()
    {
        $model = $this->context->models->UnitModel;
        $item = $model->searchByNumber(557);
        $this->assertNotNull($item);
        $this->assertEquals($item->id,5);
    }        
}
?>
