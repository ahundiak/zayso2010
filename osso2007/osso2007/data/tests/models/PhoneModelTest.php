<?php
class PhoneModelTest extends BaseModelTest
{
    function testPhoneType()
    {
        $model = $this->context->models->PhoneTypeModel;
        
        $item = $model->find(2);
        
        $this->assertEquals($item->desc,'Work');
        
        $pickList = $model->getPickList();
        //Zend::dump($pickList);
        
        $desc = $model->getDesc(3);
        $this->assertEquals($desc,'Cell');  
    }
    function testPhoneFind()
    {
        $model = $this->context->models->PhoneModel;
        
        $item = $model->find(2);
        
        $this->assertEquals($item->num,'730-7274');
    }
    function testPhoneSearch()
    {
        $model = $this->context->models->PhoneModel;

        $search = new SearchData();
        $search->personId = 1;
        $search->wantx = TRUE;
        
        $items = $model->search($search); // Zend::dump($items);
        $this->assertEquals(count($items),3);
        
        $item = $items[300];
        $this->assertEquals($item->num,'457-5943');
        $this->assertEquals($item->phoneTypeDesc,'Cell');
    }    
}
?>
