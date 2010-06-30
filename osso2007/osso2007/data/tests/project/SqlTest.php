<?php
class SqlTest extends BaseProjectTest
{
    function testTableName()
    {
        $db = $this->db;
        
        $sql = Proj_Db_Sql::tableName($db,'unit','person_unit');
        
//        $this->assertEquals($sql,'`unit` AS person_unit');      
    }
    function testColumnName()
    {
        $db = $this->db;
        
        $sql = Proj_Db_Sql::columnName($db,'person_unit','keyx','person_unit_key');
        
//        $this->assertEquals($sql,'person_unit.`keyx` AS person_unit_key');      
    }
    function testPersonSelect()
    {
        $db = $this->db;
        $select = new Proj_Db_Select($db);
        
        $select->from(
            Proj_Db_Sql::tableName($db,'person','coach'),
            array(
                Proj_Db_Sql::columnName($db,'coach','fname','coach_fname'),
                Proj_Db_Sql::columnName($db,'coach','lname','coach_lname'),
        ));
        $select->joinLeft(
            Proj_Db_Sql::tableName($db,'unit','coach_unit'),
            
            Proj_Db_Sql::columnName($db,'coach_unit','unit_id') . ' = ' .
            Proj_Db_Sql::columnName($db,'coach',     'unit_id'),
            array(
                Proj_Db_Sql::columnName($db,'coach_unit','keyx',     'coach_unit_key'),
                Proj_Db_Sql::columnName($db,'coach_unit','desc_pick','coach_unit_desc'),  
        ));
        $select->where(Proj_Db_Sql::columnName($db,'coach','person_id') . ' = ?',1);
        
        $sql = $select->__toString(); 
        
        // echo "\n" . $sql . "\n";

        $row = $db->fetchRow($select);
        // Zend::dump($row);
        
        $this->assertEquals($row['coach_lname'],    'Hundiak');
        $this->assertEquals($row['coach_unit_desc'],'R0894 Monrovia');
        
        //$result = "SELECT\n\tcoach.`fname` AS coach_fname,\n\tcoach.`lname` AS coach_lname\nFROM `person` AS coach\n";        
        //$this->assertEquals($sql,$result);      
    } 
    function testMax()
    {
        $db = $this->db;
        $select = new Proj_Db_Select($db);
        
        $divSeqNumColumn = Proj_Db_Sql::columnName($db,'team','division_seq_num');
        
        $select->from(
            Proj_Db_Sql::tableName($db,'phy_team','team'),
            array(
                Proj_Db_Sql::columnExp ($db,"max({$divSeqNumColumn})",'max_seq_num'),
        ));
        $select->where(Proj_Db_Sql::columnName($db,'team','division_id') . ' = ?',14);
        
        $sql = $select->__toString(); 
        // echo "\n" . $sql . "\n";
        
        $max = $db->fetchOne($select);
        $this->assertEquals($max,6);
    }
}
/*
 * LEFT JOIN unit AS person_unit ON person_unit.unit_id = person.unit_id
 * person_unit.keyx      AS person_unit_key,
 * person_unit.desc_pick AS person_unit_desc
 */
?>
