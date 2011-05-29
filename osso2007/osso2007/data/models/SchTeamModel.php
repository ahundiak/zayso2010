<?php
class SchTeamMap extends BaseMap
{
    protected $map = array(
        'id'             => 'sch_team_id',
        'projectId'      => 'project_id',
        'phyTeamId'      => 'phy_team_id',
        'yearId'         => 'reg_year_id',
        'seasonTypeId'   => 'season_type_id',
        'scheduleTypeId' => 'schedule_type_id',
        'unitId'         => 'unit_id',
        'divisionId'     => 'division_id',
        'sort'           => 'sortx',
        'desc'           => 'desc_short',
    );
    protected $mapx = array(
        'year'             => 'year_desc',     
        'unitKey'          => 'unit_key',
        'unitDesc'         => 'unit_desc',
        'divisionDesc'     => 'division_desc',
        'seasonTypeDesc'   => 'season_type_desc',
        'scheduleTypeKey'  => 'schedule_type_key',
        'scheduleTypeDesc' => 'schedule_type_desc',
    );
}
class SchTeamTable extends BaseTable 
{
    protected $tblName = 'sch_team';
    protected $keyName = 'sch_team_id';
    
    protected $mapClassName = 'SchTeamMap';
    
    
}
class SchTeamItem extends BaseItem
{
    protected $mapClassName = 'SchTeamMap';
    
    public $phyTeam = NULL;
    
    public function __get($name)
    {
        switch($name) 
        {   
            case 'key': return $this->getTeamKey();
        }
        return parent::__get($name);;
    }
    protected function getTeamKey()
    {
        $unit = $this->unitKey;
        $div  = $this->divisionDesc;
          
        if ((!$unit) || (!$div)) return NULL;

        $key = $unit . '-' . $div;
        
        return $key;
    }
}
class SchTeamModel extends BaseModel
{
    protected   $mapClassName = 'SchTeamMap';
    protected  $itemClassName = 'SchTeamItem';
    protected $tableClassName = 'SchTeamTable';
    
    public function search($search)
    {
        $wantPhyTeam   = FALSE;
        $wantCoachHead = FALSE;
        $wantCoachAsst = FALSE;
        $wantTeamMgr   = FALSE;
        
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $alias  = 'sch_team';
        $this->fromAll($select,$alias);
        
        if ($search->wantx) $this->joinWantx($select,$alias);

        if ($search->wantPhyTeam) {
            $wantPhyTeam = TRUE;
            $phyTeamModel = $models->PhyTeamModel;
            $phyTeamModel->joinAll  ($select,'phy_team',$alias);
            $phyTeamModel->joinWantx($select,'phy_team');
        }
        if ($search->wantCoachHead) {
            $wantCoachHead = TRUE;
            $phyTeamPersonModel = $models->PhyTeamPersonModel;
            $personModel        = $models->PersonModel;
            $unitModel          = $models->UnitModel;
            $phyTeamPersonModel->joinPhyTeamForType($select,'phyteamperson_coach_head',$alias,VolTypeModel::TYPE_HEAD_COACH);
            $personModel       ->joinAll           ($select,'person_coach_head','phyteamperson_coach_head');
            $unitModel         ->joinUnitDesc      ($select,'person_coach_head');
        }                             
        if ($search->yearId)         $select->where("{$alias}.reg_year_id      IN (?)",$search->yearId);
        if ($search->seasonTypeId)   $select->where("{$alias}.season_type_id   IN (?)",$search->seasonTypeId);
        if ($search->scheduleTypeId) $select->where("{$alias}.schedule_type_id IN (?)",$search->scheduleTypeId);
        if ($search->unitId)         $select->where("{$alias}.unit_id          IN (?)",$search->unitId);
        if ($search->divisionId)     $select->where("{$alias}.division_id      IN (?)",$search->divisionId);
        if ($search->phyTeamId)      $select->where("{$alias}.phy_team_id      IN (?)",$search->phyTeamId);
        if ($search->schTeamId)      $select->where("{$alias}.sch_team_id      IN (?)",$search->schTeamId);
        //echo $select; die();
        $select->order(array(
            "{$alias}.reg_year_id",
            "{$alias}.season_type_id",
            "{$alias}.schedule_type_id",
            "{$alias}.unit_id",
            "{$alias}.division_id",
            "{$alias}.sortx"
        ));               
        $rows = $this->db->fetchAll($select); // Zend_debug::dump($rows); die();
        $items = array();
        foreach($rows as $row)
        {
        	//if ($row['phy_team_phy_team_id']) { // Sch teams are not always deleted properly?
        	
            $item = $this->create($row,$alias);
            if ($wantPhyTeam) {
                $phyTeam = $phyTeamModel->create($row,'phy_team');
                $item->phyTeam = $phyTeam;
            }    
            if ($wantCoachHead) {  //Zend_Debug::dump($row); die();
                $person = $personModel->create($row,'person_coach_head');
                $phyTeam->coachHead = $person;
            }
            $items[$item->id] = $item;
            
        	//}
        }
        return $items;      
    }
    public function getPickList($search)
    {
        $search = clone $search;
        $search->wantx         = TRUE;
        $search->wantPhyTeam   = TRUE;
        $search->wantCoachHead = TRUE;
        $items = $this->search($search);
     
        $list  = array();
        foreach($items as $item) {
            $desc = $item->desc;
            $key  = $item->phyTeam->key;
            $name = $item->phyTeam->coachHead->namex;
            if ($key) {
                if ($desc) $desc .= ' ' . $key;
                else       $desc  = $key;
                
                if ($name) $desc .= ' ' . $name;
            }
            $list[$item->id] = $desc;
        }
        return $list;
    }
    public function joinWantx($select,$alias)
    {
        $models = $this->context->models;
        
        $models->UnitModel        ->joinUnitDesc        ($select,$alias);
        $models->YearModel        ->joinYearDesc        ($select,$alias);
        $models->DivisionModel    ->joinDivisionDesc    ($select,$alias);
        $models->SeasonTypeModel  ->joinSeasonTypeDesc  ($select,$alias);
        $models->ScheduleTypeModel->joinScheduleTypeDesc($select,$alias);
    }
}
?>
