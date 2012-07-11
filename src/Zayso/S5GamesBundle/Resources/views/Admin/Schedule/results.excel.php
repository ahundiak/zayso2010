<?php
class S5GamesResultsExport
{
    protected $counts = array();
    
    protected $widths = array
    (
        'Game'      =>  6,
        'Status 1'  => 10,
        'Status 2'  => 10,
        'PA'        =>  4,
        'DOW Time'  => 15,
        'Field'     =>  6,
        'Pool'      => 12,
            
        'Home Team' => 30,
        'Away Team' => 30,
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
    
    public function __construct($excel,$pools)
    {
        $this->excel = $excel;
        $this->pools = $pools;
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
    public function generatePoolGames($ws,$games,&$row)
    {
        $map = array(
            'Game'     => 'game',
            'Status 1' => 'status',
            'Status 2' => 'status',
            'PA'       => 'pointsApplied',
            'DOW Time' => 'date',
            'Field'    => 'field',
            'Pool'     => 'pool',
            
            'Home Team' => 'homeTeam',
            'HGS'       => 'homeGS',
            'HPM'       => 'homePM',
            'HPE'       => 'homePE',
            
            'APE'       => 'awayPE',
            'APM'       => 'awayPM',
            'AGS'       => 'awayGS',
            'Away Team' => 'awayTeam',
        );
        $row = $this->setHeaders($ws,$map,$row);
        
        foreach($games as $game)
        {
            $row++;
            $col = 0;
           
            $homeTeam   = $game->getHomeTeam();
            $awayTeam   = $game->getAwayTeam();
            $homeReport = $homeTeam->getReport();
            $awayReport = $awayTeam->getReport();
            
            $date = $game->getDate();
            $time = $game->getTime();
            
            $stamp = mktime(0,0,0,substr($date,4,2),substr($date,6,2),substr($date,0,4));
            $date = date('D',$stamp);
            
            $stamp = mktime(substr($time,0,2),substr($time,2,2));
            $time = date('h:i A',$stamp);
            
            $dtg = $date . ' ' . $time;
          
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getNum());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getStatus());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getReportStatus());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getPointsApplied());
            $ws->setCellValueByColumnAndRow($col++,$row,$dtg);
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getFieldDesc());
            $ws->setCellValueByColumnAndRow($col++,$row,$game->getPool());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$homeTeam->getTeam()->getDesc());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$homeReport->getGoalsScored());
            $ws->setCellValueByColumnAndRow($col++,$row,$homeReport->getPointsMinus());
            $ws->setCellValueByColumnAndRow($col++,$row,$homeReport->getPointsEarned());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$awayReport->getPointsEarned());
            $ws->setCellValueByColumnAndRow($col++,$row,$awayReport->getPointsMinus());
            $ws->setCellValueByColumnAndRow($col++,$row,$awayReport->getGoalsScored());
 
            $ws->setCellValueByColumnAndRow($col++,$row,$awayTeam->getTeam()->getDesc());
            
        }
        return;
    }
    public function generatePoolTeams($ws,$teams,&$row)
    {
        $map = array
        (
            'Pool'     => 'pool',
            'Team'     => 'team',
            
            'PE' => 'status',
            'PM' => 'pointsApplied',
            'GP' => 'date',
            'GW' => 'field',
            'GS' => 'pool',
            'GA' => 'homeTeam',
            'YC' => 'homeGS',
            'RC' => 'homePM',
            'CD' => 'homePE',
            'SD' => 'awayPE',
            'SP' => 'awayPM',
        );
        $row = $this->setHeaders($ws,$map,$row);
        
        foreach($teams as $team)
        {
            $row++;
            $col = 0;
           
            $report = $team->getReport();    
           
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getKey());
            $ws->setCellValueByColumnAndRow($col++,$row,$team->getParentTeamKey());
            
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getPointsEarned());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getPointsMinus());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getGamesPlayed());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getGamesWon());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getGoalsScored());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getGoalsAllowed());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getCautions());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getSendoffs());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getCoachTossed());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getSpecTossed());
            $ws->setCellValueByColumnAndRow($col++,$row,$report->getSportsmanship());
            
        }
        return;
    }
    public function generate()
    {
        // Spreadsheet
        $ss = $this->excel->newSpreadSheet();        
        $i = 0;
        $keyx = null;
        foreach($this->pools as $key => $pool)
        {
            if (substr($keyx,0,7) == substr($key,0,7))
            {
                $gameRow += 3;           
                $teamRow += 3;           
            }
            else
            {
                $gameWS = $ss->createSheet($i++);
                $gameWS->setTitle('Games ' . substr($key,0,7));
                $gameRow = 1;
                
                $teamWS = $ss->createSheet($i++);
                $teamWS->setTitle('Teams ' . substr($key,0,7));
                $teamRow = 1;
            }
            $this->generatePoolGames($gameWS,$pool['games'],$gameRow);               
            $this->generatePoolTeams($teamWS,$pool['teams'],$teamRow);               
            $keyx = $key;
        }
      //$ws = $ss->getSheet(0);
      //$ws->setTitle('Pools');
        
        // $this->generateProjectPersons ($ss->getSheet(0),$this->persons);

        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $this->excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();
        
    }
}
$export = new S5GamesResultsExport($excel,$pools);

echo $export->generate();
 
?>
