<?php
class EmailModelTest extends BaseModelTest
{
    function testEmailType()
    {
        $model = $this->context->models->EmailTypeModel;
        
        $item = $model->find(2);
        
        $this->assertEquals($item->desc,'Work');
        
        $pickList = $model->getPickList();
        //Zend::dump($pickList);
        
        $desc = $model->getDesc(1);
        $this->assertEquals($desc,'Home');  
    }
    function testEmailFind()
    {
        $model = $this->context->models->EmailModel;
        
        $item = $model->find(2);
        
        $this->assertEquals($item->address,'mike.tomlinson@qr.com');
    }
    function testEmailSearch()
    {
        $model = $this->context->models->EmailModel;

        $search = new SearchData();
        $search->personId = 1;
        $search->wantx = TRUE;
        
        $items = $model->search($search);  //Zend::dump($items);
        $this->assertEquals(count($items),2);
        
        $item = $items[66];
        $this->assertEquals($item->address,'ahundiak@ayso894.org');
        $this->assertEquals($item->emailTypeDesc,'Home');
    }    
}
?>
