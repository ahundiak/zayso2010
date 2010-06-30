<?php
class Cerad_Registry
{
    protected $data = array();
    protected $db   = NULL;
    
    function get($name) 
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return NULL;
    }
    function set($name,$value) 
    {
        $this->data[$name] = $value;
    }
    function has($name)
    {
        if (isset($this->data[$name])) return TRUE;
        return FALSE;
    }
    function getDb() { return NULL; }
}    
?>
