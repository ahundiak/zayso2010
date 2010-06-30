<?php
class PhyTeamRefereeMap extends BaseMap
{
    protected $map = array(
        'id'           => 'phy_team_referee_id',
        'phyTeamId'    => 'phy_team_id',
        'refereeId'    => 'referee_id',
        'priRegular'   => 'pri_regular',  // Was sortx
    	'priTourn'     => 'pri_tourn',
    	'maxRegular'   => 'max_regular',
    	'maxTourn'     => 'max_tourn',
        'unitId'       => 'unit_id',
        'yearId'       => 'reg_year_id',
        'seasonTypeId' => 'season_type_id',
    );
    protected $mapx = array(
        
    );
}
class PhyTeamRefereeTable extends BaseTable
{
	protected $tblName = 'phy_team_referee';
	protected $keyName = 'phy_team_referee_id';
    
    protected $mapClassName = 'PhyTeamRefereeMap';
}
class PhyTeamRefereeItem extends BaseItem
{
    protected $mapClassName = 'PhyTeamRefereeMap';
}
class PhyTeamRefereeModel extends BaseModel
{
    protected   $mapClassName = 'PhyTeamRefereeMap';
    protected  $itemClassName = 'PhyTeamRefereeItem';
    protected $tableClassName = 'PhyTeamRefereeTable';
    
    public function search($search)
    {   
        $select = new Proj_Db_Select($this->db);
        $alias  = 'phy_team_referee';
        
        $this->fromAll($select,'phy_team_referee');
        
        if ($search->wantx) {
            // $this->context->models->VolTypeModel->joinVolTypeDesc($select,$alias);
        }
        if ($search->phyTeamId) $select->where('phy_team_referee.phy_team_id IN (?)',$search->phyTeamId);
        if ($search->refereeId) $select->where('phy_team_referee.referee_id  IN (?)',$search->refereeId);
        
        if ($search->unitId)       $select->where('phy_team_referee.unit_id         IN (?)',$search->unitId);
        if ($search->yearId)       $select->where('phy_team_referee.reg_year_id     IN (?)',$search->yearId);
        if ($search->seasonTypeId) $select->where('phy_team_referee.season_type_id  IN (?)',$search->seasonTypeId);
        
        $select->order('phy_team_referee.referee_id,phy_team_referee.pri_regular');
               
        $rows = $this->db->fetchAll($select);// Zend::dump($rows);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            $items[$item->id] = $item;
        }
        return $items; 
    }
    public function joinPersonForType($select,$alias,$right,$volTypeId)
    {        
        $select->joinLeft(
            "phy_team_person AS $alias",
            "$alias.person_id = $right.person_id AND $alias.vol_type_id = $volTypeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }           
    public function joinPhyTeamForType($select,$alias,$right,$volTypeId)
    {        
        $select->joinLeft(
            "phy_team_person AS $alias",
            "$alias.phy_team_id = $right.phy_team_id AND $alias.vol_type_id = $volTypeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }           
}
?>
