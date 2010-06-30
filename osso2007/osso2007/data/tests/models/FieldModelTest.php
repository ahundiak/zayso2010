<?php
class FieldModelTest extends BaseModelTest
{
    public function testFieldSiteFind()
    {
        $model = $this->context->models->FieldSiteModel;
        
        $fieldSiteItem = $model->find(6);
        // Zend::dump($fieldSiteItem);

        $this->assertEquals($fieldSiteItem->id,6);
        $this->assertEquals($fieldSiteItem->key,'DUBLIN');
        
    }
    public function testFieldSiteSearch()
    {
        $model = $this->context->models->FieldSiteModel;
        
        $search = new SessionData();
        $search->unitId = 4;
        
        $fieldSiteItems = $model->search($search);
        
        $this->assertEquals(count($fieldSiteItems),3);
        $this->assertTrue  (isset($fieldSiteItems[6]));
        
        $fieldSiteItem = $fieldSiteItems[6];
        $this->assertEquals($fieldSiteItem->key,'DUBLIN');
        
    }
    public function testFieldSitePickList()
    {
        $model = $this->context->models->FieldSiteModel;
        
        $pickList = $model->getPickList(1);
//      Zend_Debug::dump($pickList);
                
        $this->assertEquals(count($pickList),8,'Field Count');
        $this->assertEquals($pickList[4],'Pineview');
        

    }
    public function testFieldSiteDesc()
    {
        $model = $this->context->models->FieldSiteModel;
        
        $desc = $model->getDesc(6);
        $this->assertEquals($desc,'Dublin');
    }
    /* ---------------------------------------------------------
     * Field Routines 
     */   
    public function testFieldFind()
    {
        $model = $this->context->models->FieldModel;
        
        $fieldItem = $model->find(6);

        $this->assertEquals($fieldItem->id,    6);
        $this->assertEquals($fieldItem->fieldSiteId,3);
        
        $this->assertEquals($fieldItem->key,'SPARKMAN');
        
        //Zend::dump($fieldItem);
    }
    public function testFieldDesc()
    {
        $model = $this->context->models->FieldModel;
        
        $fieldDesc = $model->getDesc(8);

        $this->assertEquals($fieldDesc,'Camp Helen');
    }
    public function testFieldSearch()
    {
        $model = $this->context->models->FieldModel;
        
        $search = new SessionData();
        $search->unitId      = 1;
        $search->fieldSiteId = 1;
        $search->wantSite = TRUE;
        $search->wantx    = TRUE;
        
        $fieldItems = $model->search($search);
        //Zend::dump($fieldItems);
                
        $this->assertEquals(count($fieldItems),12,'Field Count');
        $this->assertTrue  (isset($fieldItems[2]));
        
        $fieldItem = $fieldItems[2];
        // Zend::dump($fieldItem);
        $this->assertEquals($fieldItem->key,'WESTMIN10');
        $this->assertEquals($fieldItem->site->desc,'Westminster');
             
        $this->assertEquals($fieldItem->fieldSiteDesc,'Westminster');       
    }
    public function testFieldSave()
    {
        $model = $this->context->models->FieldModel;
        
        $item = $model->find(0);
        $item->unitId = 1;
        $item->fieldSiteId = 2;
        $item->desc = 'Test Field';
        
        $id = $model->save($item);
//      echo "Field ID $id\n";
        
        $this->assertTrue($id > 0);
        
        $item = $model->find($id);
        $this->assertEquals($item->desc,'Test Field');

        $item->desc = 'Test Update';
        $model->save($item);
        
        $item = $model->find($id);
        $this->assertEquals($item->desc,'Test Update');
        
        $model->delete($id);
        
        $item = $model->find($id);
        $this->assertEquals($item->id,NULL);      
    }
    public function testFieldCount()
    {
        $model = $this->context->models->FieldModel;
        
        $search = new SearchData();
        $search->fieldSiteId = 1;
        $count = $model->getNumberFieldsForSite($search);
        $this->assertEquals($count,12);
              
    }
    function testFieldKeySearch()
    {
        $model = $this->context->models->FieldModel;
        $item = $model->searchByKey('Dublin #1');
        $this->assertNotNull($item);
        $this->assertEquals($item->id,31);
    } 
}
?>
