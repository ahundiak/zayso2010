<?php
/* ==================================================
 * Wrap interface to the TC PDF System
 */
namespace Zayso\CoreBundle\Component\Format;

class TCPDFService
{
    public function __construct()
    {
        require_once('tcpdf/config/lang/eng.php');
        require_once('tcpdf/tcpdf.php');
    }
    public function newSpreadSheet()
    {
        return new \PHPExcel();
    }
    public function newWriter($ss)
    {
        return \PHPExcel_IOFactory::createWriter($ss, 'Excel5');
    }
    public function load($file)
    {
        return \PHPExcel_IOFactory::load($file);
    }
}
 
?>