<?php
class TeamRow   {}
class PersonRow {}

class TeamsImport extends ExcelReader
{
    protected $map = array(
        'Year'   => 'year',
        'Season' => 'season',
        'Region' => 'region',
        'Div'    => 'div',
        'S'      => 'seq',
        'Head Coach Name' => 'headCoachName',
        'HC ID'           => 'headCoachId',
        'Team Name'       => 'teamName',
    );
    protected $workSheetNames = array('Teams');
    
	function processRowData($team)
	{
       if ($team->year != 2008) return;
        
        // Validate
        $models = $this->context->models;
        
        // Map the various stuff
        $team->yearId   = $models->YearModel      ->getId($team->year);
        $team->seasonId = $models->SeasonTypeModel->getId($team->season);
        $team->divId    = $models->DivisionModel  ->getId($team->div);
        
        $region = $models->UnitModel->searchByKey($team->region);
        if ($region->id) $team->regionId = $region->id;
        else             $team->regionId = NULL;
        
        if (!$team->yearId || !$team->seasonId || !$team->divId || !$team->seq || !$team->regionId) {
        	echo "Problem with {$team->year} {$team->season} {$team->region} {$team->div} {$team->seq}\n";
			return;
        }
        // Make sure have a valid coach
        $headCoach = $models->PersonModel->find($team->headCoachId);
        if (!$headCoach->id) {
        	echo "Invalid Coach ID {$team->div} {$team->seq} {$team->headCoachName} {$team->headCoachId}\n";
        	return;
        }
        // Update the coach volunteer
        $search = new SearchData();
        $search->unitId         = $team->regionId;
        $search->yearId         = $team->yearId;
        $search->personId       = $team->headCoachId;
        $search->volTypeId      = $models->VolTypeModel->TYPE_HEAD_COACH;
        $search->divisionId     = $team->divId;
        $search->divisionSeqNum = $team->seq;
        $search->seasonTypeId   = $team->seasonId;
        
		$vol = $models->VolModel->searchOne($search);
		if (!$vol) {
			// Add one   
            $volItem = $models->VolModel->find(0);
            $volItem->volTypeId    = $search->volTypeId;
            $volItem->unitId       = $search->unitId;
            $volItem->regYearId    = $search->yearId;
            $volItem->seasonTypeId = $search->seasonTypeId;
            $volItem->divisionId   = $search->divisionId;
            $volItem->personId     = $search->personId;
            $models->VolModel->save($volItem);
		}
		// Update the physical team record
		$phyTeam = $models->PhyTeamModel->searchOne($search);
		if (!$phyTeam) 
		{
			$item = $models->PhyTeamModel->find(0);
            $item->unitId         = $search->unitId;
            $item->yearId         = $search->yearId;
            $item->seasonTypeId   = $search->seasonTypeId;
            $item->divisionId     = $search->divisionId;
            $item->divisionSeqNum = $search->divisionSeqNum;
            $item->name           = $team->teamName;
            $phyTeamId = $models->PhyTeamModel->save($item);
            
            $item = $models->SchTeamModel->find(0);
            $item->phyTeamId      = $phyTeamId;
            $item->unitId         = $search->unitId;
            $item->yearId         = $search->yearId;
            $item->divisionId     = $search->divisionId;
            $item->seasonTypeId   = $search->seasonTypeId;
            $item->scheduleTypeId = ScheduleTypeModel::TYPE_REGULAR_SEASON;
            $item->sort           = $search->divisionSeqNum;
            $schTeamId = $models->SchTeamModel->save($item);//echo "ST $schTeamId ";
            
		}
		else $phyTeamId = $phyTeam->id;
		
		// Update the team person record
		$search = new SearchData();
        $search->volTypeId = $models->VolTypeModel->TYPE_HEAD_COACH;
		$search->phyTeamId = $phyTeamId;
		
		$phyTeamPerson = $models->PhyTeamPersonModel->searchOne($search);
		if (!$phyTeamPerson) 
		{
            $item = $models->PhyTeamPersonModel->find(0);
            $item->phyTeamId = $phyTeamId;
			$item->volTypeId = $search->volTypeId;
			$item->personId  = $team->headCoachId;
			$models->PhyTeamPersonModel->save($item);
		}
		else {
			if ($phyTeamPerson->personId != $team->headCoachId)
			{
				$phyTeamPerson->personId = $team->headCoachId;
				$models->PhyTeamPersonModel->save($phyTeamPerson);
			}
		}
        echo "{$team->div} {$team->seq} {$team->headCoachName} {$headCoach->fullName}\n";
	}
}
?>