<?php
class Zayso_Repo_BaseMap
{
    protected $map  = array();
    protected $mapx = array();
    
    static $instances = array();
    
    static function getInstance($className)
    {
        if (!isset(self::$instances[$className])) {
             self::$instances[$className] = new $className;
        }
        return self::$instances[$className];   
    }
    public function __get($name)
    {
        switch($name) {
            case 'map':      return $this->map;
            case 'mapx':     return $this->mapx;
            case 'colNames': return array_values($this->map);
            case 'modNames': return array_keys  ($this->map);
        }
        return NULL;
    }
}
class Zayso_Repo_Base
{
    protected $db      = NULL;
    protected $context = NULL;

    protected $mapClassName = NULL;
    
    protected $tableCache     = NULL;
    protected $tableClassName = NULL;
    
    protected $itemCache     = NULL;
    protected $itemClassName = NULL;
    
    protected $map  = array();
    protected $mapx = array();
       
    function __construct($context,$db = NULL)
    {
        $this->context = $context;
        
        if ($db) $this->db = $db;
        else     $this->db = $context->db;
        
        if ($this->mapClassName) {
            $map = Zayso_Repo_BaseMap::getInstance($this->mapClassName);
            $this->map  = $map->map;
            $this->mapx = $map->mapx;
        }
        $this->init();
    }
    function init() {}
    
    function __get($name)
    {
        switch($name) {
            case 'table':
                if (!$this->tableCache) {
                    $tableClassName   = $this->tableClassName;
                    $this->tableCache = $this->context->tables->$tableClassName;
                }
                return $this->tableCache;
                
            case 'db': return $this->db;
            
        }
        return NULL;
    }
    public function create($rowx,$alias = NULL,$itemClassName = NULL)
    {
        /* Extract data from rox based on any alias */
        if (!$alias) $row = $rowx;
        else {
            $row = array();
            $alias .= '_';
            $aliasLen = strlen($alias);
            foreach($rowx as $key => $value) {
                if (strpos($key,$alias) === 0) {
                    $keyx = substr($key,$aliasLen);
                    if ($keyx) $row[$keyx] = $value;
                }
            }    
        }
        /* Map to camel */
        $data = array();
        foreach($this->map  as $camel => $under) {
            if (array_key_exists($under,$row)) $data[$camel] = $row[$under];
//          else                               $data[$camel] = NULL;
        }
        foreach($this->mapx as $camel => $under) {
            if (array_key_exists($under,$row)) $data[$camel] = $row[$under];
//          else                               $data[$camel] = NULL;
        }
        if (!$itemClassName) $itemClassName = $this->itemClassName;
        $item = new $itemClassName();
        $item->setModelData($data);
        
        return $item;  
    }
    public function find($id,$itemClassName = NULL)
    {
        $row = $this->table->find($id);
        
        if (!$row) {
            $row = array();
            foreach(array_values($this->map) as $under) {
                $row[$under] = NULL;
            }
        }
        return $this->create($row,NULL,$itemClassName);
    }
    function findCached($id,$itemClassName = NULL)
    {
        if (isset($this->itemCache[$id])) return $this->itemCache[$id];
        
        $item = $this->find($id,$itemClassName);
        
        if (!$item->id) return $item;
        
        $this->itemCache[$item->id] = $item;

        return $item;
    }
    // Returns an empty item with only map values set to NULL
    public function newItem($itemClassName = NULL)
    {
        $row = array();
        foreach(array_values($this->map) as $under) {
            $row[$under] = NULL;
        }
        return $this->create($row,NULL,$itemClassName);
    }
    // Returns empty item with both map and mapx values set to null
    public function newItemx($itemClassName = NULL)
    {
        $row = array();
        foreach(array_values($this->map) as $under) {
            $row[$under] = NULL;
        }
        foreach(array_values($this->mapx) as $under) {
            $row[$under] = NULL;
        }
        return $this->create($row,NULL,$itemClassName);
    }
    public function delete($id)
    {
        if (is_object($id)) $id = $id->id;
        return $this->table->delete($id);
    }
    function save($item,$insertOnlyFlag = FALSE)
    {
        $data = $item->getModelData(array_keys($this->map));
        $row  = array();
        foreach($this->map as $camel => $under)
        {
            if (array_key_exists($camel,$data)) $row[$under] = $data[$camel];
        //  else                                $row[$under] = NULL;
        }
        return $this->table->save($row,$insertOnlyFlag);        
    }
    function search($search)
    {
        return array();
    }
    function searchOne($search)
    {
        $items = $this->search($search);
        if (count($items) != 1) return NULL;
        return array_shift($items);
    }
}
class Zayso_Repo_BaseTable 
{
    protected $db      = NULL;
    protected $context = NULL;
    
    protected $tblName  = NULL;
    protected $keyName  = NULL;
    protected $colNames = array();

    protected $mapClassName = NULL;
        
    function __construct($context,$db = NULL)
    {
        $this->context = $context;
        
        if ($db) $this->db = $db;
        else     $this->db = $context->db;
        
        if ($this->mapClassName) {
            $map = Zayso_Repo_BaseMap::getInstance($this->mapClassName);
            $this->colNames = $map->colNames;    
        }
    }
    function find($id)
    {
        if ($id) return $this->db->find($this->tblName,$this->keyName,$id);
        
        return FALSE;        
    }  
    function delete($id)
    {
        return $this->db->delete($this->tblName,$this->keyName,$id);
    }
    function save($row,$insertOnlyFlag = FALSE)
    {
        $id = $row[$this->keyName];
        if (!$id || $insertOnlyFlag) {
            $this->db->insert($this->tblName,$this->keyName,$row);
            $id = $this->db->lastInsertId();
        }
        else {     
            $this->db->update($this->tblName,$this->keyName,$row);
        }
        return $id;
    } 
}
/* =========================================================
 * Base Item Stuff
 */
interface Zayso_Repo_BaseItemInterface
{
    public function getModelData($names);
    public function setModelData($data);
}
class Zayso_Repo_BaseItem implements Zayso_Repo_BaseItemInterface
{
    protected $data  = array();
    
    public function getModelData($names) 
    {
        $data  = $this->data;
        $datax = array();
        foreach($names as $name) {
            if (array_key_exists($name,$data)) $datax[$name] = $data[$name];
            else                               $datax[$name] = NULL;
        } 
        return $datax; 
    }
    public function setModelData($data)
    {
        $this->data  = $data;
    }
    public function __get($name)
    {
        if (array_key_exists($name,$this->data )) return $this->data [$name];
        return NULL;
    }
    public function get($name,$default = NULL)
    {
        if (array_key_exists($name,$this->data )) return $this->data [$name];
        return $default;
    }
    public function __set($name,$value)
    {
        if (array_key_exists($name,$this->data)) {
            $this->data[$name] = $value;
            return TRUE;
        }   
        return FALSE;
    }
}
?>
