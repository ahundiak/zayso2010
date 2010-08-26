<?php
class Osso2007_Referee_RefereeTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso2007_Referee_RefereeDirect';

  public function test_getReferees()
  {
    $direct = $this->direct;

    // Once for persons
    $personIds = array(1,2,3,4);

    $result = $direct->getReferees(array('person_id' => $personIds));

    $this->assertTrue($result->success);

    $rows = $result->rows; // Cerad_Debug::dump($rows);
    $this->assertEquals(count($rows),2);

    // Test unit lookup
    $unitIds = array(1);

    $result = $direct->getReferees(array('unit_id' => $unitIds));

    $this->assertTrue($result->success);

    $rows = $result->rows; // Cerad_Debug::dump($rows);
    $this->assertTrue(count($rows) > 100);

    foreach($rows as $row)
    {
      // printf("Referee %d %s %s %s\n",$row['eayso_reg_year'],$row['cert_desc'],$row['person_fname'],$row['person_lname']);
    }
    return;
    
    $item = $rows[1];
    Cerad_Debug::dump($rows);
    // $this->assertEquals($item['eayso_fname'],'Arthur');

    return;
  }
}
?>
