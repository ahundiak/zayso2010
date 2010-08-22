<?php
class Proj_Controller_Url
{
    protected $router = NULL;
    protected $routes;
    protected $prefix; // http://local.osso2010.org/osso2007
    
    function __construct($routes,$prefix)
    {
        if (is_array($routes)) $this->routes = $routes;
        else {
            $this->router = $routes;
            $this->routes = $this->router->getRoutes();
        }
        $this->router = NULL;
        $this->routes = NULL;
        $this->prefix = $prefix;
    }
    function linkCurrent($par1 = NULL, $par2 = NULL)
    {
        die('linkCurrent');
        if (!$this->router) return NULL;
        
        $route = $this->router->getCurrentRoute();
        
        $module  = $route->getModule();
        $control = $route->getControl();
        
        $path = $this->prefix . '/' . $module . '/' . $control; 
                
        if ($par1 !== NULL) $path .= '/' . $par1;
        if ($par2 !== NULL) $path .= '/' . $par2;
        
        return $path;
            
    }
    function link($name = NULL,$par1 = NULL, $par2 = NULL)
    {
        if (!$name) return $this->linkCurrent($par1,$par2);
        
        $pos = strrpos($name,'_');//die('Pos ' . $pos);
        if (!$pos) return NULL;
        
        $module = substr($name,0,$pos);//die($module);
        $path = $this->prefix . '/' . $module . '/' . substr($name,$pos+1);
        if ($par1 !== NULL) $path .= '/' . $par1;
        if ($par2 !== NULL) $path .= '/' . $par2;
        return $path;


        foreach(array_reverse($this->routes) as $route) {
            if ($route->isModule($module)) { //Zend::dump($route); die();
                $path = $this->prefix . '/' . $module . '/' . substr($name,$pos+1); 
                
                if ($par1 !== NULL) $path .= '/' . $par1;
                if ($par2 !== NULL) $path .= '/' . $par2;
                
                return $path;   
            }
        }
        return NULL; 
    }
    function file($path)
    {
        return $this->prefix . '/' . $path;    
    }    
}
?>
