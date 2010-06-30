<?php

class PersonModelTest extends BaseModelTest
{
    public function testPersonFind()
    {
        $model = $this->context->models->PersonModel;
        
        $item = $model->find(1);
        //Zend::dump($item);   

        $this->assertEquals($item->id,1);
        $this->assertEquals($item->fname,'Art');
        $this->assertEquals($item->lname,'Hundiak');
        $this->assertEquals($item->name, 'Art Hundiak');
        $this->assertEquals($item->namex,'Hundiak, Art');      
    }
    public function testPersonSearch()
    {
        $model = $this->context->models->PersonModel;
        
        $search = new SearchData();
        $search->lname = 'Hun';
        $search->wantx = TRUE;
        
        $items = $model->search($search);
        // Zend::dump($items);
        
        $id = 3;   
        $this->assertEquals(count($items),5,'Person Count');
        $this->assertTrue(isset($items[$id]));
        
        $item = $items[$id];
        $this->assertEquals($item->fullName,'Ethan Hundiak');
        $this->assertEquals($item->unitDesc,'R0894 Monrovia');
    }
    public function testPersonSearchPhones()
    {
        $model = $this->context->models->PersonModel;
        
        $search = new SearchData();
        $search->personId = 1;
        $search->wantx      = TRUE;
        $search->wantPhones = TRUE;
        $search->wantEmails = TRUE;
        
        $item = $model->searchOne($search);
        $this->assertEquals($item->id,1);
        
        $this->assertEquals($item->phoneHome->num,    '859-2086');
        $this->assertEquals($item->phoneCell->num,    '457-5943');
        $this->assertEquals($item->emailHome->address,'ahundiak@ayso894.org');
        
    }
}
