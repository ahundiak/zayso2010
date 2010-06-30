<?php
//class Proj_Controller_Dispatcher extends Zend_Controller_Dispatcher
class Proj_Controller_Dispatcher extends Zend_Controller_Dispatcher_Standard
{
    protected function _getController($request)
    {
        $className = $request->getParam('controllerx');
        die('Proj_Controller_Dispatcher._getController ' . $className);
        
        if (!$className) return parent::_getController($request);
        
        class_exists($className, TRUE);        
        
        return $className;
    }
    public function formatActionName($unformatted)
    {
        $formatted = $this->_formatName($unformatted, true);
        
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) $method = 'Post';            
        else                                                 $method =  NULL;
        
        return strtolower(substr($formatted, 0, 1)) . substr($formatted, 1) . 'Action' . $method;
    }
    /* Version 0.8.0 */
    public function getControllerClass($request)
    {
        //Zend::dump($request); die();
        
        $className = '';
        $modules = explode('_',$request->getParam('module'));
        foreach($modules as $module) {
            $className .= ucfirst($module);
        }
        $className .= ucfirst($request->getParam('control')) . 'Cont';
        
        if ($className && class_exists($className, TRUE)) return $className;
        
        return parent::getControllerClass($request);
    }
    public function isDispatchable(Zend_Controller_Request_Abstract $request)
    {
        $className = $this->getControllerClass($request);
        if (!$className) return true;
        
        if (class_exists($className,FALSE)) return TRUE;
        
        return parent::isDispatchable($request);
    }
    public function loadClass($className)
    {
        if (class_exists($className,FALSE)) return $className;
        
        return parent::loadClass($className);
    }
        
}
?>
