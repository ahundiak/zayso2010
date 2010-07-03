<?php
class Cerad_ArrayObjectTests extends Cerad_Tests_Base
{
  function testArrayObject()
  {
    $data = array(
      'One'   => 'one',
      'Two'   => 'two',
      'Three' => 'three');

    $obj = new ArrayObject($data,ArrayObject::ARRAY_AS_PROPS);

    $this->assertEquals($obj['One'],'one');
    $this->assertEquals($obj->One,  'one'); // Needs ARRAY_AS_PROPS

    $this->assertTrue( isset($obj->One));
    $this->assertTrue(!isset($obj->Four));

    // Add a prop
    $obj->Four = 'four';
    $this->assertEquals($obj->Four,  'four');
  //$this->assertEquals($obj->Fourx, 'four'); // No default values

    $obj['Five'] = 'five';
    $this->assertEquals($obj->Five, 'five'); // No default values

    $this->assertEquals(count($obj),5);

    $array = $obj->getArrayCopy();
    $this->assertEquals($array['Three'],'three'); // Cerad_Debug::dump($array);

    $json = json_encode($obj); // Does not work
    $json = json_encode($obj->getArrayCopy());
    $this->assertEquals($json,'{"One":"one","Two":"two","Three":"three","Four":"four","Five":"five"}');

    $obj = new ArrayObject(array(),ArrayObject::ARRAY_AS_PROPS);
    
    $obj->One = 'one';
    $this->assertEquals($obj->One,'one');
    
    $array = $obj->getArrayCopy(); // Cerad_Debug::dump($array);
    $this->assertEquals($array['One'],'one');

  }
  function test_Cerad_Array()
  {
    $obj = new Cerad_Array();

    $obj->One   = 'one';
    $obj['Two'] = 'two';

    $this->assertEquals($obj->One, 'one');
    $this->assertEquals($obj->Zero,  NULL);
    $this->assertEquals($obj['Zero'],NULL);

    $this->assertEquals($obj->get('One'),'one');
    $this->assertEquals($obj->get('Zero'),NULL);
    $this->assertEquals($obj->get('Zero','zero'),'zero');

    $data = $obj->getData();
    $this->assertEquals(count($data),2);

    $map  = array('firstName' => 'fname', 'lastName' => 'lname');
    $data = array('fname' => 'Art');

    $obj = new Cerad_Array($data,null,$map);

    $this->assertEquals($obj->fname,      'Art');
    $this->assertEquals($obj->firstName,  'Art');
    $this->assertEquals($obj['firstName'],'Art');

    $obj->lastName = 'Brown';
    $this->assertEquals($obj->lname,     'Brown');
    $this->assertEquals($obj->lastName,  'Brown');
    $this->assertEquals($obj['lastName'],'Brown');

  }
  function test_Cerad_Direct_Result()
  {
    // Test predefined success
    $result = new Cerad_Direct_Result();
    $this->assertTrue($result->success);

    $result = new Cerad_Direct_Result(array('success' => false));
    $this->assertFalse($result->success);

    $result->success = true;

    // Add some rows/records
    $rows = array
    (
      array('id' => 1, 'key' => 'one'),
      array('id' => 2, 'key' => 'two'),
      array('id' => 4, 'key' => 'four'),
    );
    $result->row =  $rows[0];
    $result->rows = $rows;

    $records = $result['records'];
    $this->assertEquals(count($records),3);

    $record = $result->record;
    $this->assertEquals($record['key'],'one');

    $recordCount = $result->recordCount;
    $this->assertEquals($recordCount,3);

    // Check the error handling
    $result = new Cerad_Direct_Result();
    $result->error = 'Error 1';
    $result->error = 'Error 2';

    $this->assertEquals($result->errorCount,2);
    $this->assertEquals($result->success,false);
  }
}
?>
