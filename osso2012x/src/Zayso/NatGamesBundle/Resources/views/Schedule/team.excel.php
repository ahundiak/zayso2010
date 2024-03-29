<?php
class NatGamesScheduleTeamExport
{
    protected $counts = array();
    
    protected $widths = array
    (
        'Game'      =>  6,
        'Status 1'  => 10,
        'Status 2'  => 10,
        'PA'        =>  4,
        'DOW Time'  => 15,
        'DOW'       =>  5,
        'Date'      =>  7,
        'Time'      => 10,
        'Field'     =>  6,
        'Pool'      => 12,
            
        'Home Team' => 26,
        'Away Team' => 26,
        
        'Referee' => 26,
        'AR1'     => 26,
        'AR2'     => 26,
        
        'HGS'       =>  5,
        'HPM'       =>  5,
        'HPE'       =>  5,
        'AGS'       =>  5,
        'APM'       =>  5,
        'APE'       =>  5,
        
        'Pool'     => 12,
        'Team'     => 30,
            
        'PE' => 8,
        'PM' => 5,
        'GP' => 5,
        'GW' => 5,
        'GS' => 5,
        'GA' => 5,
        'YC' => 5,
        'RC' => 5,
        'CD' => 5,
        'SD' => 5,
        'SP' => 5,
    );
    protected $center = array
    (
        'Game','HGS','HPM','HPE','APE','APM','AGS',
        'PE','PM','GP','GW','GS','GA','YC','RC','CD','SD','SP',
    );
    
    public function __construct($excel,$games)
    {
        $this->excel = $excel;
        $this->games = $games;
    }
    protected function setHeaders($ws,$map,$row)
    {
        $col = 0;
        foreach(array_keys($map) as $header)
        {
            $ws->getColumnDimensionByColumn($col)->setWidth($this->widths[$header]);
            $ws->setCellValueByColumnAndRow($col++,$row,$header);
            
            if (in_array($header,$this->center))
            {
                // Works but not for multiple sheets?
              //$ws->getStyle($col)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }
        return $row;
    }
    protected function setRow($ws,$map,$person,&$row)
    {
        $row++;
        $col = 0;
        foreach($map as $propName)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$person[$propName]);
        }
        return $row;
    }
    public function generateGames($ws,$games)
    {
        $map = array(
            'Game'     => 'game',
            'Date'     => 'date',
            'DOW'      => 'dow',
            'Time'     => 'time',
            'Field'    => 'field',
            'Pool'     => 'pool',
            
            'Home Team' => 'homeTeam',
            'Away Team' => 'awayTeam',
        );
        $ws->setTitle('Games');
        $row = 1;
        $row = $this->setHeaders($ws,$map,$row);
        
        foreach($games as $game)
        {   
            $row++;
            $col = 0;
           
            $homeTeam   = $game->getHomeTeam();
            $awayTeam   = $game->getAwayTeam();
            
            $date = $game->getDate();
            $time = $game->getTime();
            
            $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
            $dow  = date('D',  $stamp);
            $date = date('M d',$stamp);
            
            $stamp = mktime(substr($time,0,2),substr($time,2,2));
            $time = date('h:i A',$stamp);
            
            $dtg = $date . ' ' . $time;
          
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getNum());
            $ws->setCellValueByColumnAndRow($col++,$row,$date);
            $ws->setCellValueByColumnAndRow($col++,$row,$dow);
            $ws->setCellValueByColumnAndRow($col++,$row,$time);
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getFieldDesc());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getPool());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$homeTeam->getTeam()->getDesc());
            $ws->setCellValueByColumnAndRow($col++,$row,$awayTeam->getTeam()->getDesc());
        }
        return;
    }
    public function generate()
    {
        // Spreadsheet
        $ss = $this->excel->newSpreadSheet(); 
        $ws = $ss->getSheet(0);
        
        $ws->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $ws->getPageSetup()->setPaperSize  (\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $ws->getPageSetup()->setFitToPage(true);
        $ws->getPageSetup()->setFitToWidth(1);
        $ws->getPageSetup()->setFitToHeight(0);
        $ws->setPrintGridLines(true);
        
        $this->generateGames($ws,$this->games);
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $this->excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
        
    }
}
$export = new NatGamesScheduleTeamExport($excel,$games);

echo $export->generate();
 
?>
