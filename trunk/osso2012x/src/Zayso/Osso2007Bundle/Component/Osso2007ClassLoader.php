<?php
/* --------------------------------------
 * Registered by application
 * This will eventually go away when the class name path stuff is eliminated
 */
class Osso2007ClassLoader
{
  static $searchPath = null;
  
  public static function getPath($class)
  {
    // Scan backwards, find first upper case letter
    $category = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $class);
    if ($class == $category) return NULL;
    
    $left = substr($class,0,strlen($class) - strlen($category));
    switch($category)
    {
      /* Models */
      case 'Map':
      case 'Item':
      case 'Model':
      case 'Table':
        return "models/{$left}Model.php";
                
      /* Imports */
      case 'Import':
        return "imports/{$left}Import.php";
                
      /* Exports */
      case 'Export':
        return "exports/{$left}Export.php";
            
      /* Basic MVC Elements */
      case 'Tpl':
      case 'Cont':
      case 'View':
        $action = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $left);
        if ($action == $left) return NULL;
              
        switch($action)
        {
          case 'Base':
          case 'Web':
          case 'Txt':
          case 'Pdf':
          case 'Excel':
          case 'Print':
            $left = substr($left,0,strlen($left) - strlen($action));
            $action = preg_replace('/^([^_]*)([A-Z][^A-Z_]*)$/', '\\2', $left);
            break;
        }
        $leftx = substr($left,0,strlen($left) - strlen($action));
        return "mvc/{$leftx}/{$class}.php";
        break;
    }
    return NULL;
  }
  public static function loadClass($class)
  {
    $path = self::getPath($class);
    if (!$path) return;
        
    require self::$searchPath . $path;
  }
  public static function registerAutoload($searchPath)
  {
    self::$searchPath = $searchPath . DIRECTORY_SEPARATOR;
    spl_autoload_register(array('Osso2007ClassLoader', 'loadClass'));
  }
}
?>