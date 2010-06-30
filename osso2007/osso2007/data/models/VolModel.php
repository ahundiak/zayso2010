<?php
class VolMap extends BaseMap
{
    protected $map = array(
        'id'           => 'vol_id',
        'personId'     => 'person_id',
        'volTypeId'    => 'vol_type_id',
        'regYearId'    => 'reg_year_id',
        'seasonTypeId' => 'season_type_id',
        'unitId'       => 'unit_id',
        'divisionId'   => 'division_id',
        'note'         => 'note',
        'regForm'      => 'reg_form',
        'refresher'    => 'refresher',
        'certified'    => 'certified',
        'uniform'      => 'uniform',
        'equipment'    => 'equipment',
    );
    protected $mapx = array(
        'unitKey'        => 'unit_key',
        'unitDesc'       => 'unit_desc',
        'volTypeDesc'    => 'vol_type_desc',
        'divisionDesc'   => 'division_desc',
        'seasonTypeDesc' => 'season_type_desc',
        'year'           => 'year_desc',     
    );
}
class VolTable extends BaseTable
{
    protected $tblName = 'vol';
    protected $keyName = 'vol_id';
    
    protected $mapClassName = 'VolMap';
    
    public function deleteForPersonId($id)
    {
        return $this->db->delete($this->tblName,'person_id',$id);
    }  
}
class VolItem extends BaseItem
{
    protected $mapClassName = 'VolMap';
}
class VolModel extends BaseModel
{
    protected   $mapClassName = 'VolMap';
    protected  $itemClassName = 'VolItem';
    protected $tableClassName = 'VolTable';
        
    public function search($search)
    {
        $models = $this->context->models;
        $select = new Proj_Db_Select($this->db);
        
        $alias = 'vol';
        
        $this->fromAll($select,$alias);

        if ($search->wantx) {
            $models->UnitModel      ->joinUnitDesc      ($select,$alias);
            $models->YearModel      ->joinYearDesc      ($select,$alias);
            $models->VolTypeModel   ->joinVolTypeDesc   ($select,$alias);
            $models->DivisionModel  ->joinDivisionDesc  ($select,$alias);
            $models->SeasonTypeModel->joinSeasonTypeDesc($select,$alias);
        }        
        if ($search->volId)        $select->where("{$alias}.vol_id         IN (?)",$search->volId);
        if ($search->unitId)       $select->where("{$alias}.unit_id        IN (?)",$search->unitId);
        if ($search->yearId)       $select->where("{$alias}.reg_year_id    IN (?)",$search->yearId);
        if ($search->personId)     $select->where("{$alias}.person_id      IN (?)",$search->personId);
        if ($search->volTypeId)    $select->where("{$alias}.vol_type_id    IN (?)",$search->volTypeId);
        if ($search->divisionId)   $select->where("{$alias}.division_id    IN (?)",$search->divisionId);
        if ($search->seasonTypeId) $select->where("{$alias}.season_type_id IN (?)",$search->seasonTypeId);
        
        $select->order('vol.unit_id,vol.person_id,vol.vol_type_id');
     
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,$alias);
            $items[$item->id] = $item;
        }
        return $items;
    }
    public function getPersonPickList($search)
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->distinct();
        $select->from('vol AS vol','vol.person_id AS person_id');
        
        $select->joinLeft(
			'person AS person',
			'person.person_id = vol.person_id',
			array(
				'person.fname AS person_fname',
				'person.lname AS person_lname',
		));
		if ($search->unitId)       $select->where('vol.unit_id     IN (?)',$search->unitId);       
		if ($search->yearId)       $select->where('vol.reg_year_id IN (?)',$search->yearId);        
		if ($search->volTypeId)    $select->where('vol.vol_type_id IN (?)',$search->volTypeId);        
        if ($search->seasonTypeId) $select->where('season_type_id  IN (?)',$search->seasonTypeId);        
        if ($search->divisionId)   $select->where('division_id     IN (?)',$search->divisionId);        
        
        $select->order('person_lname,person_fname');
        
		$items = $this->db->fetchAll($select);
		$list = array();
		foreach($items as $item) {
			if (!$item['person_lname']) $item['person_lname'] = $item['person_id'];
			$list[$item['person_id']] = $item['person_lname'] . ', ' . $item['person_fname'];
		}
		return $list;
    }
    function joinVolPerson($select,$alias,$right)
    {        
        $select->joinLeft(
            "vol AS $alias",
            "$alias.person_id = $right.person_id",
            $this->table->getAliasedColumnNames($alias)
        );
    }
    function joinVolPersonForType($select,$alias,$right,$typeId)
    {        
        $select->joinLeft(
            "vol AS $alias",
            "$alias.person_id = $right.person_id AND $alias.vol_type_id = $typeId",
            $this->table->getAliasedColumnNames($alias)
        );
    }
    function deleteForPersonId($id)
    {
        if (is_object($id)) $id = $id->id;
        return $this->table->deleteForPersonId($id);
    }
}
?>
