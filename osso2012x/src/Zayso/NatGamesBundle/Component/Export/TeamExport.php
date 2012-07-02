<?php
namespace Zayso\NatGamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;

class TeamExport
{
    protected $excel = null;

    public function __construct($excel)
    {
        $this->excel = $excel;
    }
    protected function setHeaders($ws,$headers)
    {
        $col = 0;
        foreach($headers as $header)
        {
            if (isset($this->widths[$header]))
            {
                $ws->getColumnDimensionByColumn($col)->setWidth($this->widths[$header]);
            }
            $ws->setCellValueByColumnAndRow($col++,1,$header);
        }
        return 1;
    }
    protected $widths = array(
        'GID' => 5,   'PID'  => 5, 'FID' => 5, 'TR_ID' => 6, 'GT_ID' => 6, 'PT_ID' => 6,
        'DOW' => 5, 'Date' => 10, 'Time' => 6, 'Field' => 8, 'Pool' => 12, 'Game' => 6,
        'SFSP' => 5,
        
        'TR_TYPE'  => 8,'GT_TYPE' => 8,'PT_TYPE' => 8,
        
        'GT_KEY1'  => 12, 'GT_KEY2'  => 6, 'GT_KEY3' => 18, 'GT_KEY4' => 12,
        'GT_DESC1' => 26, 'GT_DESC2' => 10,
        
        'PT_KEY1'  => 16, 'PT_KEY2'  => 6, 'PT_KEY3' => 6, 'PT_KEY4' => 6,
        'PT_DESC1' =>  8, 'PT_DESC2' => 8,
    );
    protected function generateTeams($ws,$teams)
    {
        $ws->setTitle('Teams');
        
        $headers = array(
            'PID','SFSP',
            'GT_ID','GT_TYPE','GT_KEY1','GT_DESC1',
            'PT_ID','PT_TYPE','PT_KEY1','PT_DESC1'
        );
        $row = $this->setHeaders($ws,$headers);
        
        foreach($teams as $team)
        {
            $col = 0;
            $row++;
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getProject()->getId());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getSfSP()); // Sportsmanship
            
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getId());
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getType());
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey1());
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc1());
                
            $team = $team->getParent();
            if ($team)
            {
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getType());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey1());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc1());
            }
         }
    }
    public function generate($teams)
    {
        $ss = $this->excel->newSpreadSheet();
       
        $this->generateTeams($ss->getSheet(0),$teams);
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $this->excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
    }
}
?>
