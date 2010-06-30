<?php
class PhyTeamPersonMap extends BaseMap
{
    protected $map = array(
        'id'        => 'phy_team_person_id',
        'phyTeamId' => 'phy_team_id',
        'personId'  => 'person_id',
        'volTypeId' => 'vol_type_id',
    );
    protected $mapx = array(
        'volTypeDesc' => 'vol_type_desc',
    );
}
class PhyTeamPersonTable extends BaseTable
{
	protected $tblName = 'phy_team_person';
	protected $keyName = 'phy_team_person_id';
    
    protected $mapClassName = 'PhyTeamPersonMap';
}
class PhyTeamPersonItem extends BaseItem
{
    protected $mapClassName = 'PhyTeamPersonMap';
}
class PhyTeamPersonModel extends BaseModel
{
    protected   $mapClassName = 'PhyTeamPersonMap';
    protected  $itemClassName = 'PhyTeamPersonItem';
    protected $tableClassName = 'PhyTeamPersonTable';
    
    public function search($search)
    {   
        $select = new Proj_Db_Select($this->db);
        $alias  = 'phy_team_person';
        
        $this->fromAll($select,'phy_team_person');
        
        if ($search->wantx) {
            $this->context->models->VolTypeModel->joinVolTypeDesc($select,$alias);
        }
        if ($search->phyTeamId) $select->where('phy_team_person.phy_team_id IN (?)',$search->phyTeamId);
        if ($search->personId)  $select->where('phy_team_person.person_id   IN (?)',$search->personId);
        if ($search->volTypeId) $select->where('phy_team_person.vol_type_id IN (?)',$search->volTypeId);

        $select->order('phy_team_person.phy_team_id,phy_team_person.vol_type_id');
               
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
