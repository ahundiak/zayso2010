<?php
class TableTest extends BaseTest
{
    function setup()
    {
        parent::setup();
        
        $this->db = $db = new Proj_Db_Adapter($this->context->dbParams);
        $this->context->db = $db;
    }
    function test()
    {
        $phoneTypeTable = new PhoneTypeTable($this->context);
        
        $row = $phoneTypeTable->find(2);
        $this->assertEquals($row['descx'],'Work');
        
        //$phoneTypeTable->delete(array(1,2,3));
        //Zend::dump($item);
    }
    function sestCRUD()
    {
        $table = new PhoneTypeTable($this->context);
        $item = $table->find(0);
        $item->desc = 'Test';
        
        $id = $table->save($item);
        
        $item2 = $table->find($id);
        $this->assertEquals($item2->desc,'Test');
        
        $item2->desc = 'Test 2';
        $table->save($item2);
        
        $item3 = $table->find($id);
        $this->assertEquals($item3->desc,'Test 2');
        
        $table->delete($item3->id); //echo "ID  $id\n";
        //Zend::dump($item2);  
    }
}
?>
