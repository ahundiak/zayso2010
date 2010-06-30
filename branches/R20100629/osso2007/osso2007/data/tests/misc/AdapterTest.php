<?php
class AdapterTest extends BaseTest
{
    function testConstruct()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);
    }
    function testFetchAll()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);
        
        $sql = 'SELECT * FROM email_type';
        $rows = $db->fetchAll($sql);
        
        // Zend::dump($rows);
        
        $this->assertEquals(count($rows),2);
        $this->assertEquals($rows[1]['descx'],'Work');
        
        $sql = "SELECT * FROM email_type WHERE descx = 'Hello'";
        $rows = $db->fetchAll($sql);
        // Zend::dump($rows);
        $this->assertEquals(count($rows),0);
    }
    function testUpdate()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $tableName = 'phone_type';
        $keyName   = 'phone_type_id';
        $data = array(
            'phone_type_id' => 4,
            'descx'         => 'PAGER',
        );
        $cnt = $db->update($tableName,$keyName,$data);
        $this->assertEquals($cnt,1);
        
        $sql = "SELECT descx FROM phone_type WHERE phone_type_id = 4;";
        $descx = $db->fetchCol($sql);
        $this->assertEquals($descx,'PAGER');
        
        $data['descx'] = 'Pager';
            
        $cnt = $db->update($tableName,$keyName,$data);
        $this->assertEquals($cnt,1);
        
        $datax = array('id' => 4);
        $sql = "SELECT descx FROM phone_type WHERE phone_type_id = :id;";
        $descx = $db->fetchCol($sql,$datax);
        $this->assertEquals($descx,'Pager');
    }
    function testInsertAndDelete()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $tableName = 'phone_type';
        $keyName   = 'phone_type_id';
        $data = array(
            'phone_type_id' => NULL,
            'descx'         => 'Added',
        );
        $cnt = $db->insert($tableName,$keyName,$data);
        $this->assertEquals($cnt,1); 
        
        $id = $db->lastInsertId();
        $this->assertTrue($id > 5);
        
        $sql = "SELECT MAX(phone_type_id) AS id FROM phone_type;";
        $row = $db->fetchOne($sql);
        $this->assertEquals($row['id'],$id);
        
        $idx = $db->fetchCol($sql);
        $this->assertEquals($idx,$id);
        
        $cnt = $db->delete($tableName,$keyName,$idx);
        $this->assertEquals($cnt,1); 
        
    }
    function testInsertAndDeletePerson()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $tableName = 'person';
        $keyName   = 'person_id';
        $data = array(
            'fname' => 'First',
            'lname' => 'Last',
            'nname' => 'Nick',
        );
        $cnt = $db->insert($tableName,$keyName,$data);
        $this->assertEquals($cnt,1); 
        
        $id = $db->lastInsertId();
        $this->assertTrue($id > 5); //echo "New person id $id\n";
        
        $data = array(
            'person_id' => $id,
            'mname' => 'Mid  Name',
            'nname' => 'Nick Name',
        );
        $cnt = $db->update($tableName,$keyName,$data);
        $this->assertEquals($cnt,1); 
        
        $cnt = $db->delete($tableName,$keyName,$id);
        $this->assertEquals($cnt,1); 
        
    } 
    function testFind()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $tableName = 'phone_type';
        $keyName   = 'phone_type_id';
        
        $row = $db->find($tableName,$keyName,2);
        $this->assertEquals(count($row),2);
        
        $row = $db->find($tableName,$keyName,0);
        $this->assertEquals($row,FALSE);
        
        //Zend::dump($row);
        
    }
    function testPersonFetchAll()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $sql = 'SELECT person_id,fname,lname FROM person WHERE person_id IN (?);';
        $sql = $db->quoteInto($sql,array(1,2,3));
        
        $rows = $db->fetchAll($sql); // Zend::dump($rows);
        $this->assertEquals(count($rows),3);        
    }
    function sestPersonFetchAll2()
    {
        $db = new Proj_Db_Adapter($this->context->dbParams);

        $sql = 'SELECT person_id,fname,lname FROM person WHERE person_id IN (?);';
        
        $ids = array(1,2,3);
        // $sql = $db->quoteInto($sql,array(1,2,3));
        
        $rows = $db->fetchAll($sql,array($ids)); // Zend::dump($rows);
        $this->assertEquals(count($rows),3);        
    }

}
?>
