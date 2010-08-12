<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Osso2007_Team_Phy_PhyTeamPersonDirect extends Osso_Base_BaseDirect
{
  protected $tblName = 'osso2007.phy_team_person';
  protected $idName  = 'phy_team_person_id';

  public function deleteForPhyTeam($phyTeamId)
  {
    $sql = 'DELETE FROM osso2007.phy_team_person WHERE phy_team_id IN (:phy_team_id);';
    $params = array('phy_team_id' => $phyTeamId);
    return $this->db->execute($sql,$params);
  }
}
?>
