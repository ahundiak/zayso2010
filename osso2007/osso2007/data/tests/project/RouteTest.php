<?php
class RouteTest extends BaseProjectTest
{
    protected function newRoute()
    {
        return new Proj_Controller_Route(':module/:control/:id/:id2',
            array(
                  'action' => 'process',
                  'id'     => -1,
                  'id2'    => -1,
            ),
            array('id' => '\d+', 'id2' => '\d+')
        );
    }
    function testRoute3()
    {
        $route = $this->newRoute();
        
        $matched = $route->match('field/edit/10'); // Zend::dump($matched);
        $expected = array(
            'module'  => 'field',
            'control' => 'edit',
            'action'  => 'process',
            'id'      => '10',
            'id2'     => -1,
        );
        $this->assertTrue(is_array($matched));
        $this->assertEquals($matched,$expected);
        
        $route = $this->newRoute();
        $matched = $route->match('field/list'); // Zend::dump($matched);
        $expected = array(
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
        $matched = $route->match('phy_team/edit/22'); // Zend::dump($matched);
        $expected = array(
            'module'  => 'phy_team',
            'control' => 'edit',
            'action'  => 'process',
            'id'      => '22',
            'id2'     => -1,
        );
        
        $this->assertTrue(is_array($matched));
        $this->assertEquals($matched,$expected); 
        
        $controller = $route->getControllerClassName();
        $this->assertEquals($controller,'PhyTeamEditCont');
        
        // Some fails       
        $route = $this->newRoute();
        $matched = $route->match('phy_team');
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
                'access' => 'admin',
                'module' => 
                        '\b(' . 
                        'home|phy_team|sch_team|person|unit|field|field_site|event|admin|' .
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
        $route = $this->matchRoute('phy_team/edit/10');
        $this->assertEquals($route['name'],'main');
        
        $route = $this->matchRoute('admin/phy_team_players/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('public/phy_team/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('admin/home/index');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('home/index');
        $this->assertEquals($route['name'],'main');
        
        $route = $this->matchRoute('field/edit/10');
        $this->assertEquals($route['name'],'main');
        
        $route = $this->matchRoute('field_site/edit/10');
        $this->assertEquals($route['name'],'main');
        
        $route = $this->matchRoute('field_sites/edit/10');
        $this->assertEquals($route['name'],'default');
        
        $route = $this->matchRoute('something/edit/10');
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
        $route = $routes['main'];
        
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
        $this->assertEquals($link,'http://localhost/phy_team/list');
        
        $link = $url->link('phy_team_edit',10);
        $this->assertEquals($link,'http://localhost/phy_team/edit/10');
    }
}
?>
