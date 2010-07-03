<?php
class DirectBaseTest extends Cerad_Direct_Base
{
  protected $dbName  = 'dbTest';
  protected $tblName = 'org';
}
class DirectBaseTest2 extends Cerad_Direct_Base
{
  protected $dbName  = 'dbTest';
  protected $tblName = 'org';
  protected $ignoreDupKey = true;
}

class Cerad_Direct_BaseTests extends Cerad_Tests_Base
{
  protected function getRowData($key)
  {
    $row = array
    (
      'org_type_id' => 5,
      'keyx'  => $key,
      'keyxx' => $key,
      'abbv'  => $key,
      'desc1' => $key . ' Test Organization',
      'desc2' => 'Some sort of linger description',
    );
    return $row;
  }
  function testFetch()
  {
    $direct = new DirectBaseTest($this->context);

    $search = array('keyx' => 'R0894');
    $result = $direct->fetchRow($search);

    $this->assertTrue($result->success);
    $this->assertTrue($result['success']);

    $row = $result->row;
    $this->assertEquals($row['desc1'],'R0894 Monrovia, AL');

    $search = array('keyx' => array('R0894','R0160','R0498'));
    $result = $direct->fetchRows($search);

    $this->assertEquals($result->rowCount,3);

    $search['id'] = 4;
    $result = $direct->fetchRows($search);

    $this->assertEquals($result->rowCount,1);
  }
  function testInsert()
  {
    $direct = new DirectBaseTest($this->context);

    $key = 'R6666';

    // Delete
    $search = array('keyx' => $key);
    $result = $direct->fetchRow($search);
    if ($result->row) $direct->delete($result->row['id']);
    
    // Try inserting
    $row = $this->getRowData($key);
    $result = $direct->insert($row); // Cerad_Debug::dump($result); die();
    $this->assertTrue  ($result->success);
    $this->assertEquals($result->count,1);

    $id = $result->id;
    $this->assertTrue($id > 0);

    // Try duplicate
    $result = $direct->insert($row); // Cerad_Debug::dump($result); die();
    $this->assertFalse($result->success);
    $this->assertTrue($result->errorCount > 0);

    $errors = $result->errors;

    // Check the delete
    $result = $direct->delete($id);
    $this->assertTrue($result->success);
    $this->assertTrue($result->count == 1);

  }
  function testUpdate()
  {
    $direct = new DirectBaseTest($this->context);

    $key = 'R6666';
    $row = $this->getRowData($key);

    $result = $direct->insert($row);

    $id = $result->id;

    $keyxx = $key . 'xx';

    $rowx = array('id' => $id, 'keyxx' => $keyxx, 'desc2' => 'Updated');
    $result = $direct->update($rowx);
    
    $this->assertTrue($result->success);
    $this->assertTrue($result->count == 1);

    $search = array('id' => $id);
    $result = $direct->fetchRow($search);

    $this->assertEquals($result->row['keyxx'],$keyxx);

    $direct->delete($id);
    
  }
}
?>
