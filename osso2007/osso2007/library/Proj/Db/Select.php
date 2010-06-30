<?php
/* --------------------------
 * Need to isolate a bit from the Zend class since the 0.8.0 version made some major changes
 * 
 * Tried renaming the 0.7.7 class to 070 and extending from it but go some errors
 * For now just keep the 0.7.0 version in my own Zend library
 */
class Proj_Db_Select extends Zend_Db_Select
{
}
?>
