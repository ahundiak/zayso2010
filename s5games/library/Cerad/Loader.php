<?php
class Cerad_Loader
{
    public static function loadClass($className)
    {
        // See if already loaded
        if (class_exists    ($className, false) || 
            interface_exists($className, false)) 
        {
            return;
        }
        // Simple path calculation
        $path = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        include $path;
    }    
    public static function registerAutoload()
    {
        spl_autoload_register(array('Cerad_Loader', 'loadClass'));
    }
}
?>
