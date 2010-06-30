<?php
/* ================================================================
 * Probably getting way to carried away here but there could be a need
 * for a PersonVol item in which one person could have multiple
 * volunteer records attached
 * 
 * Want to do a query then process the results
 * Not worrying about updates, not sure about camel stuff
 */
class PersonVolModel extends BaseModel
{
    public function search($search)
    {
        $models = $this->context->models;
        $personModel = $models->PersonModel;
        $volModel    = $models->VolModel;
        
        $select = new Proj_Db_Select($this->db);
        
        $personModel->fromAll($select,'person');
        
        $volModel->joinVolPerson($select,'vol','person');
        
        if ($search->wantx) {
            $models->UnitModel      ->joinUnitDesc      ($select,'person');
            $models->UnitModel      ->joinUnitDesc      ($select,'vol');
            $models->YearModel      ->joinYearDesc      ($select,'vol');
            $models->VolTypeModel   ->joinVolTypeDesc   ($select,'vol');
            $models->DivisionModel  ->joinDivisionDesc  ($select,'vol');
            $models->SeasonTypeModel->joinSeasonTypeDesc($select,'vol');
        }        
        
        if ($search->personId)     $select->where('person.person_id   IN (?)',$search->personId);
        if ($search->lname)        $select->where('person.lname      LIKE ?', $search->lname . '%');
        if ($search->fname)        $select->where('person.fname      LIKE ?', $search->fname . '%');
        if ($search->personUnitId) $select->where('person.unit_id     IN (?)',$search->personUnitId);
        if ($search->yearId)       $select->where('vol.reg_year_id    IN (?)',$search->yearId);
        if ($search->volId)        $select->where('vol.vol_id         IN (?)',$search->volId);
        if ($search->volUnitId)    $select->where('vol.unit_id        IN (?)',$search->volUnitId);
        if ($search->seasonTypeId) $select->where('vol.season_type_id IN (?)',$search->seasonTypeId);
        if ($search->volTypeId)    $select->where('vol.vol_type_id    IN (?)',$search->volTypeId);
        if ($search->divisionId)   $select->where('vol.division_id    IN (?)',$search->divisionId);
        
        $select->order('person.lname,person.fname,person.person_id');
        $select->order('vol.reg_year_id,vol.season_type_id,vol.unit_id');
        $select->order('vol.vol_type_id,vol.division_id');
        
        $rows = $this->db->fetchAll($select);
        
        $items = array();
        
        foreach($rows as $row)
        {
            /* Should we check for missing person id? */
            $id = $row['person_person_id'];
            
            if (isset($items[$id])) $person = $items[$id];
            else {
                $person = $personModel->create($row,'person');
                $items[$id] = $person;
            }
            if ($row['vol_vol_id']) {
                $vol = $volModel->create($row,'vol');
                $person->addVol($vol);
                
                //if (!$person->vol) $person->vol = $vol;
            }
        }
        return $items;
    }
    /* -----------------------------------------
     * Kind of a waste to do a full join between person and vol
     * but it beats having to clone the query
     * 
     * Actually, the vol model does have a custom query for this
     */
    public function getPersonPickList($search)
    {
        $items = $this->search($search);
        $list = array();
        foreach($items as $item) {
            $list[$item->id] = $item->namex;
        }
        return $list;
    }
}
?>
