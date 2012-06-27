<?php
namespace Zayso\NatGamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;

class GameExport
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
        
        'TR_TYPE'  => 8,'GT_TYPE' => 8,'PT_TYPE' => 8,
        
        'GT_KEY1'  => 12, 'GT_KEY2'  => 6, 'GT_KEY3' => 18, 'GT_KEY4' => 12,
        'GT_DESC1' => 26, 'GT_DESC2' => 10,
        
        'PT_KEY1'  => 16, 'PT_KEY2'  => 6, 'PT_KEY3' => 6, 'PT_KEY4' => 6,
        'PT_DESC1' =>  8, 'PT_DESC2' => 8,
    );
    protected function generateGames($ws,$games)
    {
        $ws->setTitle('Games');
        
        $headers = array(
            'PID','GID','Game','DOW','Date','Time','FID','Field','Pool',
            'TR_ID','TR_TYPE',
            'GT_ID','GT_TYPE','GT_KEY1','GT_KEY2','GT_KEY3','GT_KEY4','GT_DESC1','GT_DESC2',
            'PT_ID','PT_TYPE','PT_KEY1','PT_KEY2','PT_KEY3','PT_KEY4','PT_DESC1','PT_DESC2',
        );
        $row = $this->setHeaders($ws,$headers);
        
        foreach($games as $game)
        {
            $date = $game->getDate();
            $time = $game->getTime();
            
            $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
            $dow  = date('D',  $stamp);
          //$date = date('M d',$stamp);
            
            $stamp = mktime(substr($time,0,2),substr($time,2,2));
          //$time = date('h:i A',$stamp);

            foreach($game->getTeams() as $teamRel)
            {
                $col = 0;
                $row++;
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getProject()->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getNum());
                $ws->setCellValueByColumnAndRow($col++,$row,$dow);
                $ws->setCellValueByColumnAndRow($col++,$row,$date);
                $ws->setCellValueByColumnAndRow($col++,$row,$time);
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getField()->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getField()->getDesc());
                $ws->setCellValueByColumnAndRow($col++,$row,$game->getPool());
            
                $team = $teamRel->getTeam();
            
                $ws->setCellValueByColumnAndRow($col++,$row,$teamRel->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$teamRel->getType());

                $ws->setCellValueByColumnAndRow($col++,$row,$team->getId());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getType());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey1());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey2());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey3());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey4());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc1());
                $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc2());
                
                $team = $team->getParent();
                if ($team)
                {
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getId());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getType());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey1());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey2());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey3());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey4());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc1());
                    $ws->setCellValueByColumnAndRow($col++,$row,$team->getDesc2()); 
                }
            }
            $col = 0;
            $row++;
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getProject()->getId());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getId());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getNum());
            $ws->setCellValueByColumnAndRow($col++,$row,$dow);
            $ws->setCellValueByColumnAndRow($col++,$row,$date);
            $ws->setCellValueByColumnAndRow($col++,$row,$time);
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getField()->getId());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getField()->getDesc());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getPool());
            $row++;
         }
    }
    public function generate($games)
    {
        $ss = $this->excel->newSpreadSheet();
       
        $this->generateGames($ss->getSheet(0),$games);
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $this->excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
    }
}
?>
