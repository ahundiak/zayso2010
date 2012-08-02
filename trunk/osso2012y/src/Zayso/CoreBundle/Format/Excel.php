<?php
/* ==================================================
 * Wrap interface to the excel spreasheet processing
 */
namespace Zayso\CoreBundle\Format;

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
    public function load($file)
    {
        return \PHPExcel_IOFactory::load($file);
    }
    public function setCellHorizontalAllignment($excel, $cell, $alignment = '(center|left|right)') 
    {
        switch(strtolower($alignment)) {
            case "center":
                $align = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                break;
            case "left":
                $align = \PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                break;
            case "right":
                $align = \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                break;
            default:
                $align = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                break;
        }
        $excel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal($align);
    }
 }
 
?>