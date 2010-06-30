<?php
class Cerad_SQL_Where
{
    protected $items = array();
    
    function add($name,$op,$value)
    {
        $item = "{$name} {$op} '{$value}'";
        $this->items[] = $item;
    }
    function toSQL()
    {
        $sql = NULL;
        foreach($this->items as $item)
        {
            if (!$sql) $sql  = ' WHERE ' . $item;
            else       $sql .= ' AND '   . $item;   
        }
        return $sql;
    }
}
?>
