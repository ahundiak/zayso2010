<?php
/* ----------------------------------------------
 * My own micro pear class
 */
class PEAR
{
	function PEAR() {}
	
    function isError($res)
    {
        if (is_object($res)) return TRUE;
        return FALSE;
    }
    function raiseError($msg)
    {
        echo 'PEAR Error Raised ' . $msg;
        return FALSE;
    }
}
class PEAR_Error
{
    public $error = NULL;
    
    function __construct($error)
    {
        $this->error = $error;
    }
    function getMessage()
    {
        return 'PEAR Error ' . $this->error;
    }
}
?>
