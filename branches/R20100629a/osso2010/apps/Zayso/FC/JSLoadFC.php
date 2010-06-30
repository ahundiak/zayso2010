<?php
/* ===============================================================
 * As currently implementd, jsload merges a bunch of script files together
 * and spits them all out.
 * 
 * An easier to debug implementation might have jsload only load select files
 * Wait and see how difficult the debugging is
 */
class FC_JSLoadFC
{
	function execute()
	{
		if (!isset($_GET['file']))
		{	
			$files = FC_IndexFC::$jsFiles;
		}
		else
		{
			$file = str_replace('-','/',$_GET['file']);
			$files = array($file);
		}
    ob_start();

    foreach($files as $file)
    {
    	require_once 'Zayso/' . $file . '.js';
    }
    return ob_get_clean();
	}
}
?>
