<?php
class Proj_View_Item
{
    protected $item;
    protected $view;
    protected $data = array();
    
    function __construct($view,$item)
    {
        $this->item = $item;
        $this->view = $view;
    }
    function setItem($item)
    {
        $this->item = $item;
    }
    /* ============================================
     * Since this is a view only item then anything that gets set
     * should be stored outside of the actual item
     */
    function __set($name,$value)
    {
        $this->data[$name] = $value;
    }
    function __get($name)
    {
        if (array_key_exists($name,$this->data )) $value = $this->data[$name];
        else                                      $value = $this->item->$name;
        
        // Need to ignore pick list arrays
        if (is_string($value)) return $this->view->escape($value);
        return $value;
    }
}
?>
