<?php
class Osso_Person_PersonTests extends Osso_Base_BaseTests
{
  protected $directClassName = 'Osso_Person_PersonDirect';

  public function test_insertPersonRegOrg()
  {
    $direct = $this->direct;

    $params = array
    (
      'person_reg_type_id' => 2,
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
