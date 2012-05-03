<?php
/* ==================================================
 * Wrap interface to the excel spreasheet processing
 */
namespace Zayso\CoreBundle\Component\Format;

class Excel
{
    public function newSpreadSheet()
    {
        return new \PHPExcel();
    }
    public function newWriter($ss)
    {
        return \PHPExcel_IOFactory::createWriter($ss, 'Excel5');
    }
}
 
?>