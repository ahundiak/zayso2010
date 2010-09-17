<?php
class Osso2007_Team_Phy_PhyTeamTests extends Cerad_Tests_Base
{
  public function test_parseKey()
  {
    $repoPhyTeam = $this->context->repos->phyTeam;

    $key = 'R0894-U10B-03';
    $data = $repoPhyTeam->parseKey($key);

    $this->assertEquals(1,$data['org_id']);
    $this->assertEquals(7,$data['div_id']);
    $this->assertEquals(3,$data['seq_num']);
    
  }
  public function test_getIdsForOrgSeason()
  {
    $repoPhyTeam = $this->context->repos->phyTeam;
    $search = array('org_id' => 1, 'cal_year' => 2009, 'season_type_id' => 1);
    $ids = $repoPhyTeam->getIdsForOrgSeason($search);
    
    $this->assertEquals(74,count($ids));
    $this->assertTrue(isset($ids[0]['id']));
    
  }
  public function test_getIdForProjectKey()
  {
    $repoPhyTeam = $this->context->repos->phyTeam;

    $pid = 28;  // Huntsville RT

    $id = $repoPhyTeam->getIdForProjectKey($pid,'R0160-U12G-02');
    
    $this->assertEquals(2677,$id);
  }
  public function sest_getRowForKey()
  {
    $repoSchTeam = $this->context->repos->schTeam;

    $pid = 25;

    $row = $repoSchTeam->getRowForKey($pid,'A6 498 Benson');

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
