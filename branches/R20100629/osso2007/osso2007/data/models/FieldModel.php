<?php
class FieldMap extends BaseMap
{
    protected $map = array(
        'id'          => 'field_id',
        'fieldSiteId' => 'field_site_id',
        'unitId'      => 'unit_id',      // Redundant and should go away
        'key'         => 'keyx',
        'sort'        => 'sortx',
        'desc'        => 'descx',
    );
    protected $mapx = array(
        'fieldSiteDesc' => 'field_site_desc',
    );
}
class FieldTable extends BaseTable
{
    protected $tblName = 'field';
    protected $keyName = 'field_id';
    
    protected $mapClassName = 'FieldMap';
}
class FieldItem extends BaseItem
{
    protected $mapClassName = 'FieldMap';
    
    protected $site = NULL;
    
    public function setSite($site)
    {
        $this->site = $site;
    }
    public function __get($name)
    {
        switch($name) {
            case 'site': return $this->site;
        }
        return parent::__get($name);
    }
}
class FieldModel extends BaseModel
{
    protected   $mapClassName = 'FieldMap';
    protected  $itemClassName = 'FieldItem';
    protected $tableClassName = 'FieldTable';

    protected $keyCache = array();
    
    function getPickList($unitId = NULL,$fieldSiteId = NULL)
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->from('field',array(
			'field_id  AS id',
        	'descx     AS pick',
        ));
        if ($unitId)      $select->where('unit_id       IN (?)',$unitId);
        if ($fieldSiteId) $select->where('field_site_id IN (?)',$fieldSiteId);
        
        $select->order('unit_id,field_site_id,sortx');
        
		$rows = $this->db->fetchAll($select);
		$list = array();
		foreach($rows as $row) {
			$list[$row['id']] = $row['pick'];
		}
		return $list;
    }
    function getDesc($id)
    {
        $item = $this->findCached($id);
        return $item->desc;
    }
    function getKey($id)
    {
        $item = $this->findCached($id);
        return $item->key;
    }
    function search($search)
    {
        $select = new Proj_Db_Select($this->db);
        $models = $this->context->models;
        
        $wantSite = FALSE;
        $wantx    = FALSE;
        
        $fieldAlias     = 'field';
        $fieldSiteAlias = 'fieldsite';

        $this->fromAll($select,$fieldAlias);
        
        if ($search->unitId)      $select->where('field.unit_id       IN (?)',$search->unitId);
        if ($search->fieldSiteId) $select->where('field.field_site_id IN (?)',$search->fieldSiteId);
        
        if ($search->key)         $select->where('field.descx          =  ? ',$search->key);
        
        if ($search->wantx) {
            $models->FieldSiteModel->joinFieldSiteDesc($select,$fieldAlias);    
        }
        if ($search->wantSite) {
            $wantSite = TRUE;
            $models->FieldSiteModel->joinAll($select,$fieldSiteAlias,$fieldAlias);
        }
        
        $select->order('field.unit_id,field.field_site_id,field.sortx');
        
        $rows  = $this->db->fetchAll($select);
        $items = array();
        
        foreach($rows as $row)
        {
            $item = $this->create($row,$fieldAlias);
            if ($wantSite) {
                $fieldSiteItem = $models->FieldSiteModel->create($row,$fieldSiteAlias);
                $item->setSite($fieldSiteItem);
            }
            $items[$item->id] = $item;
        }
        return $items;
    }
    public function getNumberFieldsForSite($search)
    {
        $select = new Proj_Db_Select($this->db);
        $select->from('field','count(*) AS cnt');
        
        $select->where('field.field_site_id = ?',$search->fieldSiteId);
        
        $count = $this->db->fetchCol($select);
        
        return $count;
    }
    public function searchByKey($key)
    {
        if (isset($this->keyCache[$key])) return  $this->keyCache[$key];
        
        $search = new SearchData();
        $search->wantx = TRUE;
        $search->key   = $key;
        $item = $this->searchOne($search); //Zend_Debug::dump($unit); die();
        $this->keyCache[$key] = $item;
        return $item;
    }
    function joinFieldDesc($select,$right)
    {
        $alias = $right . '_field';
        $select->joinLeft(
            "field AS $alias",
            "$alias.field_id = $right.field_id",
            array(
                "$alias.descx   AS {$alias}_desc",
                "$alias.unit_id AS {$alias}_unit_id",
                "$alias.sortx   AS {$alias}_sort",
            )
        );
        $this->context->models->FieldSiteModel->joinFieldSiteDesc($select,$alias);
    }
}
?>
