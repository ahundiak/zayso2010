<?php
class Osso2007_Project_ProjectTests extends Cerad_Tests_Base
{
  public function test_getActiveProjects()
  {
    $repoProject = $this->context->repos->project;

    $search = array();

    $rows = $repoProject->getActiveProjects($search);

    $this->assertEquals(7,count($rows));
  }
  public function test_getTeams() // Should be obsolete
  {
    $repoProject = $this->context->repos->project;

    $search = array('project_id' => 28, 'org_id' => 1, 'div_id' => 10);

    $rows = $repoProject->getTeams($search);

    $this->assertEquals(6,count($rows));

    $search = array('project_id' => 28, 'org_id' => 1, 'div_id' => array(10,11,12));

    $rows = $repoProject->getTeams($search);

    $this->assertEquals(16,count($rows));
    
  }
}
?>
