<?php
/* ========================================================
 * This is designed to create the context for a given application
 * 
 * Basically it starts things off before the controller takes over
 * 
 * The bootstrap is responsible for loading in the ProjectContext class
 */
class ApplicationLoader
{
    static function loadClass($class)
    {
    }
}
spl_autoload_register(array('ApplicationLoader','loadClass'));
spl_autoload_register(array('ProjectLoader',    'loadClass'));
spl_autoload_register(array('Zend',             'loadClass'));

class ApplicationContext extends ProjectContext
{
    static $routes = array(
        'default'         => '',
        'default_id'      => '',
        'home_login'      => 'public/home/login',
        'home_logout'     => 'public/home/logout',
        'person_edit'     => 'admin/person/edit',
        'person_list'     => 'admin/person/list',
        'phy_team_edit'   => 'admin/phy_team/edit',
        'phy_team_list'   => 'admin/phy_team/list',
        'sch_team_edit'   => 'admin/sch_team/edit',
        'sch_team_list'   => 'admin/sch_team/list',
        'field_edit'      => 'admin/field/edit',
        'field_list'      => 'admin/field/list',
        'field_site_edit' => 'admin/field_site/edit',
        'field_site_list' => 'admin/field_site/list',
        'unit_edit'       => 'admin/unit/edit',
        'unit_list'       => 'admin/unit/list',
        'event_edit'      => 'admin/event/edit',
        'admin_index'     => 'admin/admin/index',
        'sched_div_list'  => 'admin/sched_div/list',
        'sched_ref_list'  => 'admin/sched_ref/list',
        'sched_team_list' => 'admin/sched_team/list',
        'sched_field_list'=> 'admin/sched_field/list',
        
    );
        
	function __construct($params = array())
	{
		parent::__construct($params);
		
		/* Setup Front Controller, move to getFC */
		$this->fc = $fc = Zend_Controller_Front::getInstance();
        $fc->throwExceptions(TRUE);
        
        // $fc->setParam('useModules', true);
        
		///$fc->setDefaultController('index');
        
        //$this->router = $router = new Zend_Controller_RewriteRouter();
        $this->router = $router = new Zend_Controller_Router_Rewrite();

        $router->addRoute('default',
            new Zend_Controller_Router_Route('*',
            array('action'      => 'process',
                  'controllerx' => 'HomeIndexCont',
                  'redirectx'   => '',
                  'id'          => -1,
            )
        ));
        $router->addRoute('default_id',
            new Zend_Controller_Router_Route(':id',
            array('action'      => 'process',
                  'controllerx' => 'HomeIndexCont',
                  'redirectx'   => '',
                  'id'          => -1,
            ),
            array('id' => '\d+')
        ));
        $router->addRoute('home_logout',
            new Zend_Controller_Router_Route('public/home/logout',
            array('action'      => 'logout',
                  'controllerx' => 'HomeIndexCont',
                  'redirectx'   => 'public/home/logout',
            )
        ));
        $router->addRoute('home_login',
            new Zend_Controller_Router_Route('public/home/login',
            array('action'      => 'login',
                  'controllerx' => 'HomeIndexCont',
                  'redirectx'   => 'public/home/login',
            )
        ));
        
        $router->addRoute('person_edit',
            new Zend_Controller_Router_Route('admin/person/edit/:id',
            array('module'      => 'admin',
                  'controller'  => 'person',
                  'action'      => 'process',
                  'controllerx' => 'PersonEditCont',
                  'redirectx'   => 'admin/person/edit',
                  'id'          => -1,
            )
        ));
        $router->addRoute('person_list',
            new Zend_Controller_Router_Route('admin/person/list',
            array('module'      => 'admin',
                  'controller'  => 'person',
                  'action'      => 'process',
                  'controllerx' => 'PersonListCont',
                  'redirectx'   => 'admin/person/list',
            )
        ));
        $router->addRoute('phy_team_list',
            new Zend_Controller_Router_Route('admin/phyteam/list',
            array('module'      => 'admin',
                  'controller'  => 'phyTeamList',
                  'action'      => 'process',
                  'controllerx' => 'PhyTeamListCont',
                  'redirectx'   => 'admin/phyteam/list',
            )
        ));
        $router->addRoute('phy_team_edit',
            new Zend_Controller_Router_Route('admin/phyteam/edit/:id',
            array('module'      => 'admin',
                  'controller'  => 'phyTeamEdit',
                  'action'      => 'process',
                  'id'          => 0,
                  'controllerx' => 'PhyTeamEditCont',
                  'redirectx'   => 'admin/phyteam/edit',
            )
        ));
        
        $router->addRoute('sch_team_list',
            new Zend_Controller_Router_Route('admin/schteam/list',
            array('module'      => 'admin',
                  'controller'  => 'schTeamlist',
                  'action'      => 'process',
                  'controllerx' => 'SchTeamListCont',
                  'redirectx'   => 'admin/schteam/list',
            )
        ));
        $router->addRoute('sch_team_edit',
            new Zend_Controller_Router_Route('admin/schteam/edit/:id',
            array('module'      => 'admin',
                  'controller'  => 'schTeamEdit',
                  'action'      => 'process',
                  'id'          => 0,
                  'controllerx' => 'SchTeamEditCont',
                  'redirectx'   => 'admin/schteam/edit',
            )
        ));
        $router->addRoute('field_site_list',
            new Zend_Controller_Router_Route('admin/fieldsite/list/:unitId',
            array('module'      => 'admin',
                  'controller'  => 'schTeamEdit',
                  'action'      => 'process',
                  'controllerx' => 'FieldSiteListCont',
                  'redirectx'   => 'admin/fieldsite/list',
                  'unitId'      => -1,
            )
        ));
        $router->addRoute('field_site_edit',
            new Zend_Controller_Router_Route('admin/fieldsite/edit/:id',
            array('module'      => 'admin',
                  'controller'  => 'fieldSiteEdit',
                  'action'      => 'process',
                  'id'          => 0,
                  'controllerx' => 'FieldSiteEditCont',
                  'redirectx'   => 'admin/fieldsite/edit',
            )
        ));
        $router->addRoute('field_list',
            new Zend_Controller_Router_Route('admin/field/list/:unit_id/:field_site_id',
            array('module'        => 'admin',
                  'controller'    => 'fieldList',
                  'action'        => 'process',
                  'controllerx'   => 'FieldListCont',
                  'redirectx'     => 'admin/field/list',
                  'unit_id'       => -1,
                  'field_site_id' => -1,
            )
        ));
        $router->addRoute('field_edit',
            new Zend_Controller_Router_Route('admin/field/edit/:id',
            array('module'        => 'admin',
                  'controller'    => 'fieldEdit',
                  'action'        => 'process',
                  'controllerx'   => 'FieldEditCont',
                  'redirectx'     => 'admin/field/edit',
                  'id'            => -1,
            )
        ));
        $router->addRoute('unit_list',
            new Zend_Controller_Router_Route('admin/unit/list',
            array('module'        => 'admin',
                  'controller'    => 'unitList',
                  'action'        => 'process',
                  'controllerx'   => 'UnitListCont',
                  'redirectx'     => 'admin/unit/list',
            )
        ));
        $router->addRoute('unit_edit',
            new Zend_Controller_Router_Route('admin/unit/edit/:id',
            array('module'        => 'admin',
                  'controller'    => 'unitEdit',
                  'action'        => 'process',
                  'controllerx'   => 'UnitEditCont',
                  'redirectx'     => 'admin/unit/edit',
                  'id'            => -1,
            )
        ));
        $router->addRoute('event_edit',
            new Zend_Controller_Router_Route('admin/event/edit/:id',
            array('module'        => 'admin',
                  'controller'    => 'unitEdit',
                  'action'        => 'process',
                  'controllerx'   => 'EventEditCont',
                  'redirectx'     => 'admin/event/edit',
                  'id'            => -1,
            )
        ));
        $router->addRoute('admin_index',
            new Zend_Controller_Router_Route('admin/admin/index',
            array('module'        => 'admin',
                  'controller'    => 'adminHome',
                  'action'        => 'process',
                  'controllerx'   => 'AdminIndexCont',
                  'redirectx'     => 'admin/admin/index',
            )
        ));
        $router->addRoute('sched_div_list',
            new Zend_Controller_Router_Route('public/scheddiv/list',
            array('module'        => 'admin',
                  'controller'    => 'adminHome',
                  'action'        => 'process',
                  'controllerx'   => 'SchedDivListCont',
                  'redirectx'     => 'public/scheddiv/list',
            )
        ));
         
		$fc->setRouter($router);
        
		$fc->setDispatcher(new Proj_Controller_Dispatcher());
        
//		$fc->registerPlugin(new Mine_Controller_Plugin_RequireSession($this));
//		$fc->registerPlugin(new Mine_Controller_Plugin_CheckAdmin    ($this));

        // Not used but need one directory to avoid an error		
		$fc->setControllerDirectory($this->appAppDir . '/library/mvc');

        /* for now want to make sure always have an active db object */
        $db = $this->db;
        		
		/* Always create a session if the cookie exists */
		$sessionCookieName = $this->config->session->name;
		if (isset($_COOKIE[$sessionCookieName])) $session = $this->session;
		else                                     $session = NULL;
		
		/* Create the user object */
		if ($session && isset($session->user)) $user = $session->user;
		else {
			$params = $this->config->user->asArray();                 
			$user   = UserModel::create($this->db,$params);
		}
		$this->user = $user;
	}	
}
?>
