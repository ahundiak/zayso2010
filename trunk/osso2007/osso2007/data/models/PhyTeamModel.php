<?php
class PhyTeamMap extends BaseMap
{
    protected $map = array(
        'id'             => 'phy_team_id',
        'yearId'         => 'reg_year_id',
        'seasonTypeId'   => 'season_type_id',
        'unitId'         => 'unit_id',
        'divisionId'     => 'division_id',
        'divisionSeqNum' => 'division_seq_num',
        'name'           => 'name',
        'colors'         => 'colors',
        'eaysoId'        => 'eayso_id',
        'eaysoDes'       => 'eayso_des',
    );
    protected $mapx = array(
        'year'           => 'year_desc',     
        'unitKey'        => 'unit_key',
        'unitDesc'       => 'unit_desc',
        'divisionDesc'   => 'division_desc',
        'seasonTypeDesc' => 'season_type_desc',
    );    
}
class PhyTeamTable extends BaseTable 
{
    protected $tblName = 'phy_team';
    protected $keyName = 'phy_team_id';

    protected $mapClassName = 'PhyTeamMap';    
    
}
class PhyTeamItem extends BaseItem
{
    protected $mapClassName = 'PhyTeamMap';
    
    public $coachHead = NULL;
     
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
        $seq  = $this->divisionSeqNum;
      
        if ((!$unit) || (!$div) || (!$seq)) return NULL;
        
        if ($seq < 10) $seq = '0' . $seq;

        $key = $unit . '-' . $div . '-' . $seq;
        
        return $key;
    }
}
class PhyTeamModel extends BaseModel
{
    protected   $mapClassName = 'PhyTeamMap';    
    protected  $itemClassName = 'PhyTeamItem';
    protected $tableClassName = 'PhyTeamTable';
    
    public function search($search)
    {
        $wantCoachHead = FALSE;
        $wantCoachAsst = FALSE;
        $wantTeamMgr   = FALSE;
        
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $alias  = 'phy_team';
        $this->fromAll($select,$alias);
        
        if ($search->wantx) $this->joinWantx($select,$alias);
        
        if ($search->wantCoachHead) {
            $wantCoachHead = TRUE;
            $models->PhyTeamPersonModel->joinPhyTeamForType($select,'phyteamperson_coach_head',$alias,VolTypeModel::TYPE_HEAD_COACH);
            $models->PersonModel       ->joinAll           ($select,'person_coach_head','phyteamperson_coach_head');
            $models->PhoneModel    ->joinPhonePersonForType($select,'person_coach_head_phone_home','phyteamperson_coach_head',PhoneTypeModel::TYPE_HOME);
            $models->PhoneModel    ->joinPhonePersonForType($select,'person_coach_head_phone_work','phyteamperson_coach_head',PhoneTypeModel::TYPE_WORK);
            $models->PhoneModel    ->joinPhonePersonForType($select,'person_coach_head_phone_cell','phyteamperson_coach_head',PhoneTypeModel::TYPE_CELL);
            $models->UnitModel         ->joinUnitDesc      ($select,'person_coach_head');
            $models->EmailModel    ->joinEmailPersonForType($select,'person_coach_head_email_home','phyteamperson_coach_head',EmailTypeModel::TYPE_HOME);
            $models->EmailModel    ->joinEmailPersonForType($select,'person_coach_head_email_work','phyteamperson_coach_head',EmailTypeModel::TYPE_WORK);
        }                             
        if ($search->yearId)         $select->where('phy_team.reg_year_id    IN (?)',  $search->yearId);
        if ($search->seasonTypeId)   $select->where('phy_team.season_type_id IN (?)',  $search->seasonTypeId);
        if ($search->unitId)         $select->where('phy_team.unit_id        IN (?)',  $search->unitId);
        if ($search->divisionId)     $select->where('phy_team.division_id    IN (?)',  $search->divisionId);
        if ($search->divisionSeqNum) $select->where('phy_team.division_seq_num IN (?)',$search->divisionSeqNum);
        if ($search->phyTeamId)      $select->where('phy_team.phy_team_id    IN (?)',  $search->phyTeamId);

        $select->order('phy_team.reg_year_id,phy_team.season_type_id,phy_team.unit_id,phy_team_division_desc,phy_team.division_seq_num');
               
        $rows = $this->db->fetchAll($select); 
        // Cerad_Debug::dump($rows);  echo $select->__toString(); die();
        $items = array();
        foreach($rows as $row)
        {
//          Zend_Debug::dump($row); die();
            $item = $this->create($row,$alias);
            if ($wantCoachHead) { // Zend::dump($row); die();
            
                $person = $models->PersonModel->create($row,'person_coach_head');
                $person->addPhone($models->PhoneModel->create ($row,'person_coach_head_phone_home'));
                $person->addPhone($models->PhoneModel->create ($row,'person_coach_head_phone_work'));
                $person->addPhone($models->PhoneModel->create ($row,'person_coach_head_phone_cell'));
                $person->addEmail($models->EmailModel->create ($row,'person_coach_head_email_home'));
                $person->addEmail($models->EmailModel->create ($row,'person_coach_head_email_work'));
                
                $item->coachHead = $person;
            }
            $items[$item->id] = $item;
        }
        return $items;      
    }
    public function joinWantx($select,$alias)
    {
        $models = $this->context->models;
        
        $models->UnitModel      ->joinUnitDesc      ($select,$alias);
        $models->YearModel      ->joinYearDesc      ($select,$alias);
        $models->DivisionModel  ->joinDivisionDesc  ($select,$alias);
        $models->SeasonTypeModel->joinSeasonTypeDesc($select,$alias);   
    }
    public function getHighestSeqNum($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->from('phy_team','max(division_seq_num) AS max_seq_num');
        
        $select->where('phy_team.reg_year_id    = ?',$search->yearId);
        $select->where('phy_team.season_type_id = ?',$search->seasonTypeId);
        $select->where('phy_team.unit_id        = ?',$search->unitId);
        $select->where('phy_team.division_id    = ?',$search->divisionId);
        
        $seqNum = $this->db->fetchCol($select);
        
        return $seqNum;
    }
    public function getPickList($search)
    {
        $search = clone $search;
        $search->wantx = TRUE;
        $search->wantCoachHead = TRUE;
        $items = $this->search($search);
     
        $list  = array();
        foreach($items as $item) {
            $key  = $item->key;
            $name = $item->coachHead->namex;
            if ($name) $key .= ' ' . $name;
            $list[$item->id] = $key;
        }
        return $list;    
    }
}
?>
