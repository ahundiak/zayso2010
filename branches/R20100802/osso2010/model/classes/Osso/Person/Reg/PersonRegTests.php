<?php
class Osso_Person_Reg_PersonRegTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso_Person_Reg_PersonRegDirect';

  public function test_insertPersonRegOrg()
  {
    $direct = $this->direct;

    $params = array
    (
      'person_reg_id'      => 1,
      'org_id'             => 1,
    );
    $results = $this->direct->insertPersonRegOrg($params);
    $this->assertTrue($results['success']);

    $results = $this->direct->insertPersonRegOrg($params);
    $this->assertTrue($results['success']);

    
  }
}
?>
