<?php
class UnitMap extends BaseMap
{
    protected $map = array(
        'id'         => 'unit_id',
        'unitTypeId' => 'unit_type_id',
        'parentId'   => 'parent_id',
        'key'        => 'keyx',
        'sort'       => 'sortx',
        'descPick'   => 'desc_pick',
        'prefix'     => 'prefix',
        'descLong'   => 'desc_long',
    );
    protected $mapx = array(
        'unitTypeDesc' => 'unit_type_desc',
    );
}    
class UnitTable extends BaseTable
{   
    protected $tblName = 'unit';
    protected $keyName = 'unit_id';
    
    protected $mapClassName = 'UnitMap';
}
class UnitItem extends BaseItem
{
    protected $mapClassName = 'UnitMap';
}

class UnitModel extends BaseModel
{ 
    protected $mapClassName   = 'UnitMap';
    protected $itemClassName  = 'UnitItem';
    protected $tableClassName = 'UnitTable';
    
    protected $keyCache = array();
    
    public function getPickList()
    {
        $select = new Proj_Db_Select($this->db);
        
        $select->from('unit',
            array(
			     'unit_id   AS id',
        	     'desc_pick AS pick',
        ));
        $select->order('keyx');
        
		$items = $this->db->fetchAll($select);
		$list = array();
		foreach($items as $item) {
			$list[$item['id']] = $item['pick'];
		}
		return $list;
    }
    public function getDesc($id)
    {
        $item = $this->findCached($id);
        return $item->descPick;
    }
    public function getKey($db,$id)
    {
        $item = $this->findCached($id);
        return $item->keyx;
    }
    public function search($search)
    {
        $select = new Proj_Db_Select($this->db);
    
        $this->fromAll($select,'unit');

        if ($search->wantx) {
            $this->context->models->UnitTypeModel->joinUnitTypeDesc($select,'unit');
        }        
        if ($search->unitId) $select->where('unit.unit_id  IN (?)',$search->unitId);
        if ($search->key)    $select->where('unit.keyx      =  ? ',$search->key);
        
        $select->order('unit.sortx');
       
        $rows = $this->db->fetchAll($select);
        $items = array();
        foreach($rows as $row)
        {
            $item = $this->create($row,'unit');
            $items[$item->id] = $item;
        }
        return $items;
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
    public function searchByNumber($number)
    {
        if (!is_numeric($number)) return NULL;
        if (($number < 1) || ($number > 9999)) return NULL;
        $key = sprintf('R%04d',$number);
        return $this->searchByKey($key);
    }
    public function joinUnitDesc($select,$right,$rightKey='unit_id')
    {
        $left = $right . '_unit';
        
        $select->joinLeft(
            "unit AS {$left}",
            "{$left}.unit_id = {$right}.{$rightKey}",
            array(
                "{$left}.keyx      AS {$left}_key",
                "{$left}.desc_pick AS {$left}_desc",
            )
        );
    }
}
?>
