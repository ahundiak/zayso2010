<?php
class Proj_Locator_Table
{
    protected $context = NULL;
    protected $db      = NULL;
    protected $tables  = array();
    
    function __construct($context,$db = NULL)
    {
           $this->context = $context;
           $this->db      = $db;
    }
    function __get($name)
    {
        if (!isset($this->tables[$name])) {
            $className = $name;
            $table = new $className($this->context,$this->db);
            $this->tables[$name] = $table;
        }
        return $this->tables[$name];    
    }
}
?>
