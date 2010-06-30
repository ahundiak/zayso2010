<?php
/* ---------------------------------
 * Basically a wrapper for a row
 * which allows one to do intelligent updates
 */
class Proj_Db_Table_RowUpdate
{
    protected $table;
    protected $tableInfoCols;
    protected $tableInfoPrimary;
    
    protected $row;
    protected $changed;
    
    function __construct($table = NULL, $row = NULL)
    {
        if ($table) $this->setTable($table);
        if ($row)   $this->setRow  ($row);
    }
    function setTable($table)
    {
        $this->table = $table;
        
        $info = $table->info();
        $this->tableInfoCols    = $info['cols'];
        $this->tableInfoPrimary = $info['primary'];
    }
    function setRow($row)
    {        
        $this->row = $row;
        $this->changed  = array();
    }
    function __set($camel,$value)
    {
        $under = array_search($camel, $this->tableInfoCols);
        if (!$under) {
            throw new Zend_Db_Table_Row_Exception("column '$camel' not in row helper");
        }
        if (isset($this->changed[$under])) {
            $this->changed[$under] = $value;
            return;
        }
        $valuex = $this->row->$camel;
        if ($valuex === $value) return;
        
        $this->changed[$under] = $value;
        
    }
    function update()
    {
        if (count($this->changed) < 1) return $this->row;
        
        $primaryKeyUnder = $this->tableInfoPrimary;
        $primaryKeyCamel = $this->tableInfoCols[$primaryKeyUnder];
        $primaryKeyValue = $this->row->$primaryKeyCamel;
        
        $db    = $this->table->getAdapter();
        $where = $db->quoteInto("{$primaryKeyUnder} = ?", $primaryKeyValue);
      
        return $this->table->update($this->changed,$where);       
    }
}
?>
