<?php
class Osso2007_Team_Sch_SchTeamTests extends Cerad_Tests_Base
{
  public function test_getIdForProjectKey()
  {
    $repoSchTeam = $this->context->repos->schTeam;

    $pid = 25; // Madison RT

    $id = $repoSchTeam->getIdForProjectKey($pid,'A6 498 Benson');
    $this->assertEquals(3333,$id);
  }
  public function test_getRowForProjectKey()
  {
    $repoSchTeam = $this->context->repos->schTeam;

    $pid = 25;

    $row = $repoSchTeam->getRowForProjectKey($pid,'A6 498 Benson');

    $this->assertEquals(3333,$row['sch_team_id']);
  }
  public function sest_addTeam()
  {
    $repoSchTeam = $this->context->repos->schTeam;

    $datax = array
    (
      'project_id' => 31,
      'org_id'     =>  1,
      'div_id'     =>  7,
      'key'        => 'U10B A1',
    );
    $result = $reposchTeam->addTeam($datax);

    $this->assertTrue($result->success);
  }
}
?>
