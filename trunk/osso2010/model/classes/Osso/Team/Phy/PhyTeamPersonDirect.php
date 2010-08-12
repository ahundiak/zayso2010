<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Osso_Team_Phy_PhyTeamPersonDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'team_phy_person';
  protected $ignoreDupKey = false;

  public function deleteForPhyTeam($phyTeamId)
  {
    $sql = 'DELETE FROM team_phy_person WHERE team_phy_id IN (:phy_team_id);';
    $params = array('phy_team_id' => $phyTeamId);
    return $this->db->execute($sql,$params);
  }
}
?>
