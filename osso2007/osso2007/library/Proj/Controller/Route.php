<?php
class Proj_Controller_Route extends Zend_Controller_Router_Route
{
    public function getControllerClassName()
    {
        $name = '';
        $modules = explode('_',$this->_values['module']);
        foreach($modules as $module) {
            $name .= ucfirst($module);
        }
        $name .= ucfirst($this->_values['control']) . 'Cont';

        return $name;   
    }
    public function isModule($name)
    {
        if (!isset($this->_requirements['module'])) return FALSE;
        $module  = $this->_requirements['module'];
        
        $exp = $this->_regexDelimiter . $module . $this->_regexDelimiter;
                
        if (preg_match($exp, $name)) return TRUE;
        
        return FALSE;
    }
    protected function getValue($name)
    {
        if (isset($this->_values  [$name])) return $this->_values  [$name];
        if (isset($this->_params  [$name])) return $this->_params  [$name];
        if (isset($this->_defaults[$name])) return $this->_defaults[$name];
        return NULL;
    }
    public function getAccess () { return $this->getValue('access');  }    
    public function getModule () { return $this->getValue('module');  }    
    public function getControl() { return $this->getValue('control'); }    
}
?>
