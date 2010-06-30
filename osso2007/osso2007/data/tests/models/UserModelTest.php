<?php
class UserModelTest extends BaseModelTest
{
    function testMemberFind()
    {
        $model = $this->context->models->MemberModel;
        
        $id = 1;
        $item = $model->find($id);
        //Zend::dump($item);
        
        $this->assertEquals($item->id,$id);
        $this->assertEquals($item->name,     'Art');
        $this->assertEquals($item->firstName,'Art');
    }
    function testMemberSearch()
    {
        $model = $this->context->models->MemberModel;
        
        $search = new SearchData();
        $search->accountId = 1;
        $search->name = 'Art';
        $search->wantx = TRUE;
        
        $items = $model->search($search);
        //Zend::dump($items);
        
        $this->assertEquals(count($items),1);
        $item = $items[1];
        $this->assertEquals($item->id,1);
        $this->assertEquals($item->name,     'Art');
        $this->assertEquals($item->firstName,'Art');
        $this->assertEquals($item->unitDesc, 'R0894 Monrovia');
    }
    function testAccountFind()
    {
        $model = $this->context->models->AccountModel;
        
        $id = 1;
        
        $item = $model->find($id);
        
//      Zend::dump($item);
        
        $this->assertEquals($item->id,$id);
        $this->assertEquals($item->userName,'ahundiak');
        $this->assertEquals($item->lastName,'Hundiak');
        
    }
    function testAccountSearch()
    {
        $model = $this->context->models->AccountModel;
        
        $search = new SearchData();
        //$search->accountId = 1;
        $search->user = 'ahundiak';
        
        $item = $model->searchOne($search);
        //Zend::dump($item);
        
        $this->assertEquals($item->id,1);
        $this->assertEquals($item->name,    'Hundiak');
        $this->assertEquals($item->lastName,'Hundiak');
    }
    function testAccountDuplicate()
    {
        $model = $this->context->models->AccountModel;
        $db    = $this->context->db;
        
        /* Clear any older entries */
        $search = new SearchData();
        $search->user = 'duplicate';
        $item = $model->searchOne($search);
        if ($item) $model->delete($item);
        
        // Setup to fail because missing password
        $item = $model->find(0);
        $item->user = 'duplicate';
        $item->name = 'name';

        try {
            $id = $model->save($item);
            $this->assertTrue($id > 0);
        }
        catch (PDOException $e) {
            $this->assertFalse($db->isDuplicateEntryError($e));
        }
        // Insert one
        $item->pass = 'pass';
        $id = $model->save($item);
        $this->assertTrue($id > 0);
        
        // Catch duplicate entry
        $item = $model->find(0);
        $item->user = 'duplicate';
        $item->name = 'name';
        $item->pass = 'pass';
        try {
            $id = $model->save($item);
            $this->assertTrue($id > 0);
        }
        catch (PDOException $e) {
            $this->assertTrue($db->isDuplicateEntryError($e));
        }
        
        // And delete
        $model->delete($id);
    }
    function testUserCreate()
    {
        $model = $this->context->models->UserModel;
        
        $defaults = array(
            'reg_year_id'    => 6,
            'season_type_id' => 1,
            'unit_id'        => 1,
            'unit_desc'      => 'R0894 Monrovia', // Save a database hit?
        );
        
        $user = $model->create($defaults);
        
        $this->assertFalse($user->isAuth);
        $this->assertFalse($user->isMember);
        $this->assertFalse($user->isAdmin);
        
        $this->assertEquals($user->defaultYearId,6);
        $this->assertEquals($user->defaultSeasonTypeId,1);
        $this->assertEquals($user->defaultUnitId,1);
        $this->assertEquals($user->defaultUnitDesc,'R0894 Monrovia');
    }
    function testUserLoad()
    {
        $model = $this->context->models->UserModel;
        
        $defaults = array(
            'reg_year_id'    => 6,
            'season_type_id' => 1,
            'unit_id'        => 1,
            'unit_desc'      => 'R0894 Monrovia',
        );
        $memberId = 1;
        $user = $model->load($defaults,$memberId);
        //Zend::dump($user);
        
        $this->assertTrue($user->isAuth);
        $this->assertTrue($user->isMember);
        $this->assertTrue($user->isPerson);
        $this->assertTrue($user->isAdmin);
        
        $this->assertEquals($user->name,'Art Hundiak');
        
        $memberId = 2;
        $user = $model->load($defaults,$memberId);
        
        $this->assertTrue ($user->isAuth);
        $this->assertTrue ($user->isMember);
        $this->assertTrue ($user->isMember);
        $this->assertFalse($user->isAdmin);
               
    }    
    function testUserRefereePickList()
    {
        $model = $this->context->models->UserModel;
        
        $defaults = array(
            'reg_year_id'    => 6,
            'season_type_id' => 1,
            'unit_id'        => 1,
            'unit_desc'      => 'R0894 Monrovia',
        );
        $memberId = 1;
        $user = $model->load($defaults,$memberId);
                
        $this->assertTrue($user->isPerson);
        
        $pickList = $user->refereePickList;
        
        $this->assertEquals(count($pickList),2);
        $this->assertEquals($pickList[3],'Hundiak, Ethan');
        
        // Zend_Debug::dump($pickList);
    }
    function testUserPersonIds()
    {
        $model = $this->context->models->UserModel;
        
        $defaults = array(
            'reg_year_id'    => 6,
            'season_type_id' => 1,
            'unit_id'        => 1,
            'unit_desc'      => 'R0894 Monrovia',
        );
        $memberId = 1;
        $user = $model->load($defaults,$memberId);
        
        $personIds = $user->personIds;
        //Zend_Debug::dump($personIds);
        $this->assertEquals(count($personIds),3);
        $this->assertTrue (isset($personIds[3]));
        $this->assertFalse(isset($personIds[4]));
        
    }
}
?>
