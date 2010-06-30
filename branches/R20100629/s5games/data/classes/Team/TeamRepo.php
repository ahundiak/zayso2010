<?php
/* ---------------------------------------------
 * Should probably check the data type and support
 * arrays just for speed
 */
class Team_TeamRepo extends Base_BaseRepo
{
    protected $context = NULL;
    
    public function insertPhyTeam($item)
    {
    	$data = array(
    		'phy_team_id'      => 0,
    	    'reg_year_id'      => $item->yearId,
    		'unit_id'          => $item->leagueId,
    		'division_id'      => $item->divId,
    		'division_seq_num' => $item->divSeqNum,
    		'season_type_id'   => $item->seasonTypeId,
    	);
    	$phyTeamId = $this->saveRow('phy_team','phy_team_id',$data);  

    	$data['sch_team_id']      = 0;
    	$data['phy_team_id']      = $phyTeamId;
    	$data['schedule_type_id'] = $item->schTypeId;
    	
    	unset($data['division_seq_num']);
    	
    	$this->saveRow('sch_team','sch_team_id',$data);
    	
    	return $phyTeamId;
   }
   public function insertTeamPerson($item)
   {
    	$data = array(
    		'phy_team_person_id' => 0,
    	    'phy_team_id'        => $item->teamId,
    		'person_id'          => $item->personId,
    		'vol_type_id'        => $item->typeId,
    	);
    	$id = $this->saveRow('phy_team_person','phy_team_person_id',$data);  

        return $id;
   }
}
?>