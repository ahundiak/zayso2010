<?php
class Proj_Controller_Action extends Zend_Controller_Action
{
    protected $adminOnly = FALSE;
    
    function init()
    {
        parent::init();
               
        $this->context = ProjectContext::getInstance('controller');
    }
    public function link($routeName = NULL,$par1 = NULL,$par2 = NULL)
    {
        return $this->context->url->link($routeName,$par1,$par2);
    }
    function isAuthorized()
    {
        if ($this->adminOnly) {
            if (!$this->context->user->isAdmin) return FALSE;
//            $response = $this->getResponse();
//            $response->setRedirect($this->link('home_auth'));
//            return FALSE;
        }
        return TRUE;
    }}
?>
