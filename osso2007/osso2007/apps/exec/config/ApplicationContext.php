<?php
/* ========================================================
 * This is designed to create the context for a given application
 * 
 * Basically it starts things off before the controller takes over
 * 
 * The bootstrap is responsible for loading in the ProjectContext class
 */
class ApplicationContext extends ProjectContext
{
	function __construct($params = array())
	{
		parent::__construct($params);
	}	
}
?>
