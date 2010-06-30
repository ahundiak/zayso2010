<?php
class Proj_Locator_Model
{
    protected $context = NULL;
    protected $db      = NULL;
    protected $models  = array();
    
    function __construct($context,$db = NULL)
    {
           $this->context = $context;
           $this->db      = $db;
    }
    function __get($name)
    {
        if (!isset($this->models[$name])) {
           
            if (strpos($name,'Model') === FALSE) $className = $name . 'Model';
            else                                 $className = $name;
            
            $model = new $className($this->context,$this->db);
            $this->models[$name] = $model;
        }
        return $this->models[$name];    
    }
}
?>
