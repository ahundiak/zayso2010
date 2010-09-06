<?php
/* ----------------------------------------------------
 * Use this to transform internal route names to actual
 * http uri/urls
 */
class Osso2007_Url
{
    protected $context;

    protected $prefix; // http://local.osso2010.org/osso2007

    protected $webPath; // '/osso2007/'
    protected $webBase; // http://local.osso2010.org/osso2007/

    public function __construct($context)
    {
      $this->context = $context;
      $this->init();

      /* Web Directory for url generation */
      $this->appWebDir = dirname($_SERVER['SCRIPT_NAME']);

      /* Server name is often handy */
      $this->appServerName = $_SERVER['SERVER_NAME'];

      /* Absolute url */
      $this->appUrlAbs = "http://{$this->appServerName}{$this->appWebDir}";

      $this->prefix = $this->appUrlAbs . '/';

      $this->prefix = '';
    }
    protected function init()
    {
      // Independent
      $this->webPath = dirname($_SERVER['SCRIPT_NAME']) . '/';
      $this->webBase = 'http://' . $_SERVER['SERVER_NAME'] . $this->webPath;
    }
    function link($name,$par1 = NULL, $par2 = NULL)
    {
      $pos = strrpos($name,'_');//die('Pos ' . $pos);
      if (!$pos) return NULL;
  
      $module = substr($name,0,$pos);//die($module);
      $path = $this->prefix . $module . '/' . substr($name,$pos+1);
      if ($par1 !== NULL) $path .= '/' . $par1;
      if ($par2 !== NULL) $path .= '/' . $par2;
      return $path;
    }
    function file($path)
    {
        return $this->prefix . $path;    
    }    
}
?>
