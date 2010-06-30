<?php
class LocatorTest extends BaseTest
{
    function setup()
    {
        parent::setup();
        
        $this->db = $db = new Proj_Db_Adapter($this->context->dbParams);
        $this->context->db = $db;
        
        $this->tables = $tables = new Proj_Locator_Table($this->context);
        $this->context->tables = $tables;
        
        $this->models = $models = new Proj_Locator_Model($this->context);
        $this->context->models = $models;
        
    }
    function testTableLocator()
    {
        $phoneTypeTable = $this->context->tables->PhoneTypeTable;
        $row = $phoneTypeTable->find(2);
        $this->assertEquals($row['descx'],'Work');
    }
    function testModelLocator()
    {
        $phoneTypeModel = $this->context->models->PhoneTypeModel;
        $item = $phoneTypeModel->find(2);
        $this->assertEquals($item->desc,'Work');
    }
    function testPersonPhoneEmail()
    {   
        $select = new Proj_Db_Select($this->db);
        
        $select->from('person AS person',array(
            'person.person_id AS person_person_id',
            'person.fname     AS person_fname',
            'person.lname     AS person_lname'));
            
       $select->joinLeft(
            'phone AS phone',
            'phone.person_id = person.person_id',
            array(
                'phone.phone_id      AS phone_phone_id',
                'phone.phone_type_id AS phone_phone_type_id',
                'phone.num           AS phone_num',
            )
        );
        $select->joinLeft(
            'email AS email',
            'email.person_id = person.person_id',
            array(
                'email.email_id      AS email_email_id',
                'email.email_type_id AS email_email_type_id',
                'email.address       AS email_address',
        ));
        $select->joinLeft(
            'email_type AS email_type',
            'email_type.email_type_id = email.email_type_id',
            array(
                'email_type.descx AS email_email_type_desc',
        ));
             
        $select->where('person.person_id = ?',1);
       
        $rows = $this->db->fetchAll($select);  // Zend_Debug::dump($rows);
       
        $persons = array();
        $emails  = array();
        $phones  = array();
       
        $personModel = $this->context->models->PersonModel;
        $phoneModel  = $this->context->models->PhoneModel;
        $emailModel  = $this->context->models->EmailModel;
           
        foreach($rows as $row) {
            $personId = $row['person_person_id'];
            if (isset($persons[$personId])) $person = $persons[$personId];
            else {
                $persons[$personId] = $person = $personModel->create($row,'person');
            }
            
            $emailId = $row['email_email_id'];
            if ($emailId && !isset($emails[$emailId])) {
                $emails[$emailId] = $email = $emailModel->create($row,'email');
                $person->addEmail($email); 
            }
            
            $phoneId = $row['phone_phone_id'];
            if ($phoneId && !isset($phones[$phoneId])) {
                $phones[$phoneId] = $phone = $phoneModel->create($row,'phone');
                $person->addPhone($phone); 
            }
            
        }   
        Zend_Debug::dump($persons);
            
//      $this->assertEquals(TRUE,FALSE);        
   }
}
?>
