<?php
class Proj_Controller_Plugin_Authorized extends Zend_Controller_Plugin_Abstract
{
    protected $context = NULL;
    
    function __construct($context)
    {
        $this->context = $context;
    }
    function preDispatch($request)
    {
        //Zend::dump($request); die();
        $access = $request->getParam('access');
        switch($access) {
            case 'admin': 
            if (!$this->context->user->isAdmin) {
                $request->setParam('access', 'public');
                $request->setParam('module', 'home');
                $request->setParam('control','auth');
                //die('Unauthorized access attempt');
            }
            break;
        }
    }
}
?>
