<?php
class Osso2007_Person_PersonTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso2007_Person_PersonDirect';

  public function test_getCerts()
  {
    $direct = $this->direct;

    $personIds = array(1,2,3,4);

    $result = $direct->getCerts(array('person_id' => $personIds));

    $this->assertTrue($result->success);

    $rows = $result->rows; // Cerad_Debug::dump($rows);
    $this->assertEquals(count($rows),4);

    $item = $rows[1];
    // $this->assertEquals($item['eayso_fname'],'Arthur');

    return;
  }
}
?>
