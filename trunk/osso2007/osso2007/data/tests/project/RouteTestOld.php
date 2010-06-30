<?php
class RouteTest extends BaseProjectTest
{

    function testRoute()
    {
        $route = new Zend_Controller_Router_Route('admin/fieldsite/list/:unitId/:fieldSiteId',
            array('module'      => 'admin',
                  'controller'  => 'schTeamEdit',
                  'action'      => 'process',
                  'controllerx' => 'FieldSiteListCont',
                  'redirectx'   => 'admin/fieldsite/list',
                  'unitId'      => -1,
                  'fieldSiteId' => -1,
            )
        );
        //$matched = $route->match('admin/fieldsite/list/10/20');
        $url = $route->assemble();
        //$this->assertEquals($url,'admin/fieldsite/list/-1/-1');
        $this->assertEquals($url,'admin/fieldsite/list');
        
        $route = new Zend_Controller_Router_Route('admin/fieldsite/list/:unitId/:fieldSiteId',
            array('module'      => 'admin',
                  'controller'  => 'schTeamEdit',
                  'action'      => 'process',
                  'controllerx' => 'FieldSiteListCont',
                  'redirectx'   => 'admin/fieldsite/list',
                  'unitId'      => NULL,
                  'fieldSiteId' => NULL,
            )
        );
        $url = $route->assemble(array('unitId' => '33', 'fieldSiteId' => '66'));
        $this->assertEquals($url,'admin/fieldsite/list/33/66');        
    }
    function testRoute2()
    {
        $route = new Zend_Controller_Router_Route('admin/field/list/:id',
            array('module'      => 'admin',
                  'controller'  => 'schTeamEdit',
                  'action'      => 'process',
                  'controllerx' => 'FieldSiteListCont',
                  'redirectx'   => 'admin/fieldsite/list',
                  'id'          => -1,
            )
        );
        $matched = $route->match('admin/field/list/10'); // Zend::dump($matched);
        $this->assertTrue(is_array($matched));
        
    }
    protected function newRoute()
    {
        return new Proj_Controller_Route(':access/:module/:control/:id/:id2',
            array(
                  'action' => 'process',
                  'id'     => -1,
                  'id2'    => -1,
            ),
            array('access' => 'admin', 'id' => '\d+', 'id2' => '\d+')
        );
    }
    function testRoute3()
    {
        $route = $this->newRoute();
        
        $matched = $route->match('admin/field/list/10'); // Zend::dump($matched);
        $expected = array(
            'access'  => 'admin',
            'module'  => 'field',
            'control' => 'list',
            'action'  => 'process',
            'id'      => '10',
            'id2'     => -1,
        );
        $this->assertTrue(is_array($matched));
        $this->assertEquals($matched,$expected);
        
        $route = $this->newRoute();
        $matched = $route->match('admin/field/list'); // Zend::dump($matched);
        $expected = array(
            'access'  => 'admin',
            'module'  => 'field',
            'control' => 'list',
            'action'  => 'process',
            'id'      => '-1',
            'id2'     => -1,
        );
        
        $this->assertTrue(is_array($matched));
        $this->assertEquals($matched,$expected);  
        
        $controller = $route->getControllerClassName();
        $this->assertEquals($controller,'FieldListCont');

        // Next one        
        $route = $this->newRoute();
        $matched = $route->match('admin/phy_team/edit/22'); // Zend::dump($matched);
        $expected = array(
            'access'  => 'admin',
            'module'  => 'phy_team',
            'control' => 'edit',
            'action'  => 'process',
            'id'      => '22',
            'id2'     => -1,
        );
        
        $this->assertTrue(is_array($matched));
        $this->assertEquals($matched,$expected); 
        
        $url = $route->url(44);
        $this->assertEquals($url,'admin/phy_team/edit/44');
        
        $controller = $route->getControllerClassName();
        $this->assertEquals($controller,'PhyTeamEditCont');
        
        // Some fails       
        $route = $this->newRoute();
        $matched = $route->match('admin/phy_team');
        $this->assertFalse($matched);
        
        $route = $this->newRoute();
        $matched = $route->match('admin');
        $this->assertFalse($matched);
        
        $route = $this->newRoute();
        $matched = $route->match('public/phy_team/edit');
        $this->assertFalse($matched);
        
        $route = $this->newRoute();
        $matched = $route->match('adminx/phy_team/edit');
        $this->assertFalse($matched);
    }
    protected function newDefaultRoute()
    {
        return new Proj_Controller_Route('*',
            array(
                  'access'  => 'public',
                  'module'  => 'home',
                  'control' => 'index',
                  'action'  => 'process',
            )
        );
    }
    protected function newDefaultRouteId()
    {
        return new Proj_Controller_Route(':id/:id2',
            array(
                  'access'  => 'public',
                  'module'  => 'home',
                  'control' => 'index',
                  'action'  => 'process',
                  'id'      => -1,
                  'id2'     => -1,
            ),
            array('id' => '\d+', 'id2' => '\d+')
        );
    }
    function testDefaultRoute()
    {
        $route = $this->newDefaultRoute();
        $matched = $route->match('');
        $expected = array(
            'access'  => 'public',
            'module'  => 'home',
            'control' => 'index',
            'action'  => 'process',
        );
        $this->assertEquals($matched,$expected); 
        // Zend::dump($matched);  
         
        $route = $this->newDefaultRoute();
        $matched = $route->match('aaa');
        $expect = $expected;
        $expect['aaa'] = NULL;
        $this->assertEquals($matched,$expect);
        
        $route = $this->newDefaultRoute();
        $matched = $route->match('aaa/bbb');
        $expect = $expected;
        $expect['aaa'] = 'bbb';
        $this->assertEquals($matched,$expect); 
        $route = $this->newDefaultRoute();
        
        $matched = $route->match('23');
        $expect = $expected;
        $expect['23'] = NULL;
        $this->assertEquals($matched,$expect); 
    }
    function testDefaultRouteId()
    {
        $route = $this->newDefaultRouteId();
        $matched = $route->match('25');
        $expected = array(
            'access'  => 'public',
            'module'  => 'home',
            'control' => 'index',
            'action'  => 'process',
            'id'      => '25',
            'id2'     => -1,
        );
        $this->assertEquals($matched,$expected); 
        // Zend::dump($matched);
        
        $route = $this->newDefaultRouteId();
        $matched = $route->match('25x');
        $this->assertFalse($matched);
        
    }
    function getRoutes()
    {
        /* The default and the default id routes are basically the same
         * Still not too clear on the whole matching thing but it really should not
         * matter which of the default routes actually get matched
         */
        $routes['default'] = new Proj_Controller_Route('*',
            array(
                  'access'  => 'public',
                  'module'  => 'home',
                  'control' => 'index',
                  'action'  => 'process',
                  'id'      => -1,
                  'id2'     => -1,
            )
        );
        $routes['default_id'] = new Proj_Controller_Route(':id/:id2',
            array(
                  'access'  => 'public',
                  'module'  => 'home',
                  'control' => 'index',
                  'action'  => 'process',
                  'id'      => -1,
                  'id2'     => -1,
            ),
            array('id' => '\d+', 'id2' => '\d+')
        );        
        $routes['public'] = new Proj_Controller_Route(':access/:module/:control/:id/:id2',
            array(
                  'access' => 'public',
                  'action' => 'process',
                  'id'     => -1,
                  'id2'    => -1,
            ),
            array(
                'access' => 'public',
                'module' => 
                        '\b(' .
                        'home' . 
                        ')\b', 
                'id' => '\d+', 'id2' => '\d+'
            )
        );
        $routes['admin'] = new Proj_Controller_Route(':access/:module/:control/:id/:id2',
            array(
                  'access' => 'admin',
                  'action' => 'process',
                  'id'     => -1,
                  'id2'    => -1,
            ),
            array(
                'access' => 'admin',
                'module' => 
                        '\b(' . 
                        'phy_team|sch_team|person|unit|field|field_site|event|admin|' .
                        'sched_div|sched_ref|sched_team|sched_field' . 
                        ')\b', 
                'id' => '\d+', 'id2' => '\d+'
            )
        );
        return $routes;
    }
    function matchRoute($path)
    {
        $routes = $this->getRoutes();        
        foreach(array_reverse($routes) as $name => $route) {
            $matched = $route->match($path);
            if ($matched) {
                $matched['name'] = $name;
                return $matched;
            }
        }
        return FALSE;
    }
    function testMatchRoute()
    {
        $route = $this->matchRoute('admin/phy_team/edit/10');
        $this->assertEquals($route['name'],'admin');
        
        $route = $this->matchRoute('admin/phy_team_players/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('public/phy_team/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('admin/home/index');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('public/home/index');
        $this->assertEquals($route['name'],'public');
        
        $route = $this->matchRoute('admin/field/edit/10');
        $this->assertEquals($route['name'],'admin');
        
        $route = $this->matchRoute('admin/field_site/edit/10');
        $this->assertEquals($route['name'],'admin');
        
        $route = $this->matchRoute('admin/field_sites/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('admin/something/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('');
        $this->assertEquals($route['name'],'default_id');
        
        $route = $this->matchRoute('/');
        $this->assertEquals($route['name'],'default_id');
        $this->assertEquals($route['id'],-1);
        
        $route = $this->matchRoute('help');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('25');
        $this->assertEquals($route['name'],'default_id');
        $this->assertEquals($route['id'],25);
    }
    function testRouteIsModule()
    {
        $routes = $this->getRoutes();
        $route = $routes['admin'];
        
        $this->assertEquals($route->isModule('phy_team'  ),TRUE);
        $this->assertEquals($route->isModule('phy_teamx' ),FALSE);
        $this->assertEquals($route->isModule('field'     ),TRUE);
        $this->assertEquals($route->isModule('field_site'),TRUE);
        $this->assertEquals($route->isModule('field_sit' ),FALSE);
    }
    function testExp()
    {
        $exp = '#\b(phy_team|sch_team|person)\b#';
        
        $this->assertEquals(preg_match($exp,'phy_team'), 1);
        $this->assertEquals(preg_match($exp,'phy_teamx'),0);
    }
    function testUrl()
    {
        $routes = $this->getRoutes();
        $url = new Proj_Controller_Url($routes,'http://localhost');
        
        $link = $url->link('phy_team_list');
        $this->assertEquals($link,'http://localhost/admin/phy_team/list');
        
        $link = $url->link('phy_team_edit',10);
        $this->assertEquals($link,'http://localhost/admin/phy_team/edit/10');
    }
}
?>
