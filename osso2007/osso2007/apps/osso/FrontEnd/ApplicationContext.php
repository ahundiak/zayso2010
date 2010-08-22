<?php
/* ========================================================
 * This is designed to create the context for a given application
 * 
 * Basically it starts things off before the controller takes over
 * 
 * The bootstrap is responsible for loading in the ProjectContext class
 */
// spl_autoload_register(array('ProjectLoader',    'loadClass'));
// spl_autoload_register(array('Zend_Loader',     'loadClass')); // Cerad_Loader does not work

class ApplicationContext extends ProjectContext
{
  protected $routes = NULL;
          
  function init()
  {
    parent::init();

    /* Setup Front Controller, move to getFC */
    $this->fc = $fc = Zend_Controller_Front::getInstance();
    $fc->throwExceptions(TRUE);
        
    $fc->setParam('noErrorHandler', true);
    $fc->setParam('noViewRenderer', true);

    // Router initialization
    $routes = $this->getRoutes();
    $this->router = $router = new Zend_Controller_Router_Rewrite();

    $router->addRoutes($routes);
         
    $fc->setRouter($router);
    
    // Url generator */
    $this->url = new Proj_Controller_Url($router,$this->appUrlAbs);
        
    // Dispatcher 
    $fc->setDispatcher(new Proj_Controller_Dispatcher());

    // Not used but need one directory to avoid an error		
    $fc->setControllerDirectory($this->appAppDir . '/library/mvc');
        		
    // Session for user
    $session = $this->session;

    /* Create the user object */
    if ($session && isset($session->user)) $user = $session->user;
    else
    {
      $params = $this->config['user'];
      $user   = $this->models->UserModel->create($params);
    }
    $this->user = $user;
  }
  function getRoutes()
  {
    if ($this->routes) return $this->routes;
        
    /* The default and the default id routes are basically the same
     * Still not too clear on the whole matching thing but it really should not
     * matter which of the default routes actually get matched
     */
    $routes['default'] = new Proj_Controller_Route('*',
      array(
        'module'  => 'home',
        'control' => 'index',
        'action'  => 'process',
        'id'      => -1,
        'id2'     => -1,
      )
    );
    $routes['default_id'] = new Proj_Controller_Route(':id/:id2',
      array(
        'module'  => 'home',
        'control' => 'index',
        'action'  => 'process',
        'id'      => -1,
        'id2'     => -1,
      ),
      array('id' => '\d+', 'id2' => '\d+')
    );        
    $routes['main'] = new Proj_Controller_Route(':module/:control/:id/:id2',
      array(
        'action' => 'process',
        'id'     => -1,
        'id2'    => -1,
      ),
      array(
        'module' => 
          '\b(' .
          'home|account|member|user|sched_div|sched_ref|' . 
          'phy_team|sch_team|person|unit|field|field_site|event|admin|' .
          'sched_team|sched_field|ref_points|ref_avail|import|report' .
          ')\b', 
          'id'  => '.+', // '\d+',
          'id2' => '\d+'
        )
    );
    $this->routes = $routes;
        
    return $routes;
  }
}
?>
