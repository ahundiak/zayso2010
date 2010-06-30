<?php
class FieldSiteMap extends BaseMap
{
    protected $map = array(
        'id'     => 'field_site_id',
        'unitId' => 'unit_id',
        'key'    => 'keyx',
        'sort'   => 'sortx',
        'desc'   => 'descx',
    );
}
class FieldSiteTable extends BaseTable
{
    protected $tblName = 'field_site';
    protected $keyName = 'field_site_id';
    
    protected $mapClassName = 'FieldSiteMap';
}
class FieldSiteItem extends BaseItem
{
    protected $mapClassName = 'FieldSiteMap';
}
class FieldSiteModel extends BaseModel
{
    protected   $mapClassName = 'FieldSiteMap';
    protected  $itemClassName = 'FieldSiteItem';
    protected $tableClassName = 'FieldSiteTable';
    
    function getPickList($unitId = NULL)
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->from('field_site',array(
			'field_site_id AS id',
        	'descx         AS pick',
        ));
        if ($unitId) $select->where('unit_id IN (?)',$unitId);
        
        $select->order('unit_id,keyx');
        
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
        
        $this->fromAll($select,'field_site');
        
        if ($search->unitId) $select->where('field_site.unit_id IN (?)',$search->unitId);
        
        $select->order('field_site.unit_id,field_site.sortx');
        
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,'field_site');
            $items[$item->id] = $item;
        }
        return $items;
    }
    function joinFieldSiteDesc($select,$right)
    {
        $left = $right . '_field_site';
        
        $select->joinLeft(
            "field_site AS {$left}",
            "{$left}.field_site_id = {$right}.field_site_id",
            array(
                "{$left}.field_site_id  AS {$left}_id",
                "{$left}.descx          AS {$left}_desc",
                "{$left}.unit_id        AS {$left}_unit_id",
            )
        );
    }
}
?>
