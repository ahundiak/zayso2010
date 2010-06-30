<?php
//class Proj_Controller_Dispatcher extends Zend_Controller_Dispatcher
class Proj_Controller_Dispatcher extends Zend_Controller_Dispatcher_Standard
{
    // Returns completely formatted action method name
    public function getActionMethod(Zend_Controller_Request_Abstract $request)
    {
        $action = $request->getActionName();
        if (empty($action)) {
            $action = 'process';
            $request->setActionName($action);
        }
        if ($request->isPost()) return $action . 'ActionPost';
        else                    return $action . 'Action';
    }
    public function getControllerClass($request)
    {
        $className = '';
        $modules = explode('_',$request->getParam('module'));
        foreach($modules as $module) {
            $className .= ucfirst($module);
        }
        $className .= ucfirst($request->getParam('control')) . 'Cont';
  
        return $className;
    }
    // Don't like to overide this much but it's better than the alternative
    public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
    {
        $this->setResponse($response);

        // Get and load the controller class
        $className = $this->getControllerClass($request);
        if (!$className) {
             $className = $this->getDefaultControllerClass($request);
        }
        if (!class_exists($className, TRUE)) {
            require_once 'Zend/Controller/Dispatcher/Exception.php';
            throw new Zend_Controller_Dispatcher_Exception("Controller '$className' not found");
        }
        /**
         * Instantiate controller with request, response, and invocation 
         * arguments; throw exception if it's not an action controller
         */
        $controller = new $className($request, $this->getResponse(), $this->getParams());
        if (!$controller instanceof Zend_Controller_Action) {
            require_once 'Zend/Controller/Dispatcher/Exception.php';
            throw new Zend_Controller_Dispatcher_Exception("Controller '$className' is not an instance of Zend_Controller_Action");
        }

        /**
         * Retrieve the action name
         */
        $action = $this->getActionMethod($request);

        /**
         * Dispatch the method call
         */
        $request->setDispatched(true);

        // by default, buffer output
        $disableOb = $this->getParam('disableOutputBuffering');
        if (empty($disableOb)) {
            ob_start();
        }
        $controller->dispatch($action);
        if (empty($disableOb)) {
            $content = ob_get_clean();
            $response->appendBody($content);
        }

        // Destroy the page controller instance and reflection objects
        $controller = null;
    }    
}
?>
