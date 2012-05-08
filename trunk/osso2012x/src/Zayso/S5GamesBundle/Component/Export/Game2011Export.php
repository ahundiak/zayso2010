<?php
namespace Zayso\S5GamesBundle\Component\Export;

use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Component\DataTransformer\PhoneTransformer;


class Game2011Export
{
    protected $excel = null;
    protected $manager = null;
    
    protected $games    = null;
    protected $phyTeams = null;
    protected $schTeams = null;
    protected $fields   = null;
    
    protected $schTeamKeys = null;
    
    public function __construct($manager,$excel)
    {
        $this->manager   = $manager;
        $this->excel     = $excel;
    }
    protected $widths = array(
        'Game'      =>  6,
        'Date'      => 10,
        'DOW'       =>  6,
        'Time'      =>  6,
        'Div'       =>  6,
        'Field'     => 10,
        'Type'      =>  6,
        'Bracket'   => 10,
        'Home Team' => 20,
        'Away Team' => 20,
        
        'Key'    => 24,
        'Region' =>  6,
        'Age'    =>  6,
        'Gender' =>  6,
        'Name'   => 20,
    );
    protected function setHeaders($ws,$map)
    {
        $col = 0;
        foreach(array_keys($map) as $header)
        {
            $ws->getColumnDimensionByColumn($col)->setWidth($this->widths[$header]);
            $ws->setCellValueByColumnAndRow($col++,1,$header);
        }
        return 1;
    }
    protected function setRow($ws,$map,$item,$row)
    {
        $row++;
        $col = 0;
        foreach($map as $propName)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$item[$propName]);
        }
        return $row;
    }
    protected function generateGames($ws)
    {
        $map = array(
            'Game'      => 'game_num',
            'Date'      => 'game_date',
            'DOW'       => 'game_dow',
            'Time'      => 'game_time',
            'Div'       => 'game_div',
            'Field'     => 'game_field',
            'Type'      => 'game_type',
            'Bracket'   => 'game_bracket',
            'Home Team' => 'home_name',
            'Away Team' => 'away_name',
        );
        $ws->setTitle('Games');
        
        $row = $this->setHeaders($ws,$map);

        $items = $this->getGames();
        foreach($items as $item)
        {
            $row = $this->setRow($ws,$map,$item,$row);            
        }
    }
    protected function generatePhyTeams($ws)
    {
        $map = array(
            'Region' => 'region',
            'Div'    => 'div',
            'Age'    => 'age',
            'Gender' => 'gender',
            'Key'    => 'key',
            'Name'   => 'name',
        );
        $ws->setTitle('Physical Teams');
        
        $row = $this->setHeaders($ws,$map);

        $items = $this->getPhyTeams();
        foreach($items as $item)
        {
            $row++;
            $col = 0;
            foreach($map as $propName)
            {
                $ws->setCellValueByColumnAndRow($col++,$row,$item[$propName]);
            }
        }
    }
    protected function generateSchTeams($ws)
    {
        $map = array(
            'Div'    => 'div',
            'Age'    => 'age',
            'Gender' => 'gender',
            'Key'    => 'key',
            'Name'   => 'phy_team_key',
        );
        $ws->setTitle('Schedule Teams');
        
        $row = $this->setHeaders($ws,$map);

        $items = $this->getSchTeams();
        foreach($items as $item)
        {
            $row++;
            $col = 0;
            foreach($map as $propName)
            {
                $ws->setCellValueByColumnAndRow($col++,$row,$item[$propName]);
            }
        }
    }
    /* =========================================================================
     * Main entry point
     */
    public function generate()
    {
        $excel = $this->excel;
        
        $ss = $excel->newSpreadSheet();
        
        $this->generateGames   ($ss->getSheet(0));
        $this->generatePhyTeams($ss->createSheet(1));
        $this->generateSchTeams($ss->createSheet(2));
        
        // Output
        $ss->setActiveSheetIndex(0);
        $objWriter = $excel->newWriter($ss); // \PHPExcel_IOFactory::createWriter($ss, 'Excel5');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name
        return ob_get_clean();

    }
    /* ======================================================================
     * Extract individual schedule team
     */
    protected function getSchTeam($div,$brac,$teamName)
    {
        $type = null;
        
        $phyTeam = $this->getPhyTeam($div,$brac,$teamName);
        if ($phyTeam) $phyTeamKey = $phyTeam['key'];
        else          $phyTeamKey = null;
        
        switch($brac)
        {
            case 'FINAL': $type = 'FM'; break;
            case 'SF':    $type = 'SF'; break;
                
            case 'NA':     $type = 'A'; break;
            case 'Brac 1': $type = 'A'; break;
            case 'Brac 2': $type = 'B'; break;
            case 'Brac 3': $type = 'C'; break;
            
            default:
                die('Bracket ' . $brac . "\n");
        }
        $schTeamKey = 'TBD';
        
        $pool = $div . ' ' . $type . ' ';
        
        switch($type)
        {
            case 'A':  // U10B A
            case 'B':
            case 'C':
                if ($phyTeamKey)
                {   
                    // Already defined team
                    if ($this->phyTeams[$phyTeamKey]['pool']) return;
                    {
                        $seq = 1;
                        $pool = $div . ' PP ' . $type;
                        while(isset($this->schTeamKeys[$pool . $seq])) $seq++;
                        $schTeamKey = $pool . $seq;
                        
                        $this->phyTeams[$phyTeamKey]['pool'] = $schTeamKey;
                        $this->schTeamKeys[$schTeamKey] = $phyTeamKey;
                    }
                }
                break;
            default:
                switch($teamName)
                {
                    // U10B, U12B
                    case 'Boys 1st in Bracket':
                        $schTeamKey = $pool . 'A 1ST';
                        if (isset($this->schTeamKeys[$schTeamKey]))
                        {
                            $schTeamKey = $pool . 'B 1ST';
                        }
                        $this->schTeamKeys[$schTeamKey] = 'TBD';
                        break;

                    // U10G
                    case 'Girls Bracket 1 - 1s': $schTeamKey = $pool . 'A 1ST'; break;
                    case 'Girls Bracket 1 - 2n': $schTeamKey = $pool . 'A 2ND'; break;
                    case 'Girls Bracket 1 3rd' : $schTeamKey = $pool . 'A 3RD'; break;
                    case 'Girls Bracket 1 4th' : $schTeamKey = $pool . 'A 4TH'; break;
                    case 'Girls Bracket 1 5th' : $schTeamKey = $pool . 'A 5TH'; break;
                    case 'Girls Bracket 1 - 3r': $schTeamKey = $pool . 'A 3RD'; break;
                    case 'Girls Bracket 1 - 4t': $schTeamKey = $pool . 'A 4TH'; break;
                    
                    case 'Girls Bracket 2 - 1s': $schTeamKey = $pool . 'B 1ST'; break;
                    case 'Girls Bracket 2 - 2n': $schTeamKey = $pool . 'B 2ND'; break;
                    case 'Girls Bracket 2 3rd' : $schTeamKey = $pool . 'B 3RD'; break;
                    case 'Girls Bracket 2 4th' : $schTeamKey = $pool . 'B 4TH'; break;
                    case 'Girls Bracket 2 5th' : $schTeamKey = $pool . 'B 5TH'; break;
                    case 'Girls Bracket 2 - 3r': $schTeamKey = $pool . 'B 3RD'; break;
                    case 'Girls Bracket 2 - 4t': $schTeamKey = $pool . 'B 4TH'; break;
                     
                    case 'Girls Bracket 3 - 1s': $schTeamKey = $pool . 'C 1ST'; break;
                    case 'Girls Bracket 3 - 2n': $schTeamKey = $pool . 'C 2ND'; break;
                    case 'Girls Bracket 3 3rd' : $schTeamKey = $pool . 'C 3RD'; break;
                    case 'Girls Bracket 3 4th' : $schTeamKey = $pool . 'C 4TH'; break;
                    case 'Girls Bracket 3 5th' : $schTeamKey = $pool . 'C 5TH'; break;
                    
                    case 'Girls loser 7:30 fie': 
                        $schTeamKey = $div . ' CM RUP SF1'; 
                        if (isset($this->schTeamKeys[$schTeamKey]))
                        {
                            $schTeamKey = $div . ' CM RUP SF2';
                        }
                        $this->schTeamKeys[$schTeamKey] = 'TBD';
                        break;
                        
                    case 'Girls winner 7:30 fi': 
                        $schTeamKey = $div . ' FM WIN SF1'; 
                        if (isset($this->schTeamKeys[$schTeamKey]))
                        {
                            $schTeamKey = $div . ' FM WIN SF2';
                        }
                        $this->schTeamKeys[$schTeamKey] = 'TBD';
                        break;
                        
                    case 'Girls Bracket 2nd ?': 
                        $schTeamKey = $div . ' SF 2nd? A'; 
                        if (isset($this->schTeamKeys[$schTeamKey]))
                        {
                            $schTeamKey = $div . ' SF 2nd? B';
                        }
                        $this->schTeamKeys[$schTeamKey] = 'TBD';
                        break;
                         
                    case 'Girls Bracket 1 Winn' : $schTeamKey = $pool . 'A 1ST';     break;
                    case 'Girls Bracket 2 Winn' : $schTeamKey = $pool . 'B 1ST';     break;
                    case 'Girls Bracket 3 Winn' : $schTeamKey = $pool . 'C 1ST';     break;
                    case 'Girls Wildcard - hig' : $schTeamKey = $pool . 'WC';        break;
                    
                    case 'G - 1st in Points': $schTeamKey = $pool . 'A 1ST'; break;
                    case 'G - 2nd in Points': $schTeamKey = $pool . 'A 2ND'; break;
                    case 'G - 3rd in Points': $schTeamKey = $pool . 'A 3RD'; break;
                    case 'G - 4th in Points': $schTeamKey = $pool . 'A 4TH'; break;
                     
                    case 'B - 1st in Points': $schTeamKey = $pool . 'A 1ST'; break;
                    case 'B - 2nd in Points': $schTeamKey = $pool . 'A 2ND'; break;
                    
                    case 'B - 3rd in Points': $schTeamKey = $div . ' CM A 3RD'; break;
                    case 'B - 4th in Points': $schTeamKey = $div . ' CM A 4TH'; break;
                     
                    case 'G - Loser B1#1 v B2#' : $schTeamKey = $div . ' CM RUP SF1';     break;
                    case 'G - Loser B2#1 v B1#' : $schTeamKey = $div . ' CM RUP SF2';     break;
                    case 'G - Loser B1#3 v B2#' : $schTeamKey = $div . ' CM RUP SF3';     break;
                    case 'G - Loser B2#3 v B1#' : $schTeamKey = $div . ' CM RUP SF4';     break;
                    
                    case 'G - Winner B1#1 v B2' : $schTeamKey = $div . ' FM WIN SF1';     break;
                    case 'G - Winner B2#1 v B1' : $schTeamKey = $div . ' FM WIN SF2';     break;
                    case 'G - Winner B1#3 v B2' : $schTeamKey = $div . ' FM WIN SF3';     break;
                    case 'G - Winner B2#3 v B1' : $schTeamKey = $div . ' FM WIN SF4';     break;
                    
                    case 'B - Winner #1 v #4' : $schTeamKey = $div . ' FM WIN SF1';     break;
                    case 'B - Winner #2 v #3' : $schTeamKey = $div . ' FM WIN SF2';     break;
                    
                    case 'G - Loser #2 v #3' : $schTeamKey = $div . ' CM RUP SF1';     break;
                    case 'G - Loser #1 v #4' : $schTeamKey = $div . ' CM RUP SF2';     break;
                    case 'G - Winner #1 v #4': $schTeamKey = $div . ' FM WIN SF1';     break;
                    case 'G - Winner #2 v #3': $schTeamKey = $div . ' FM WIN SF2';     break;
               }
        }
        if ($schTeamKey == 'TBD') echo sprintf('Sch Team Key ' . $div . ' ' . $teamName . "\n");
        
        $team = array();
        
        $team['key'] = $schTeamKey;
       
        $team['div']    = $div;
        $team['age']    = substr($div,0,3);
        $team['gender'] = substr($div,3,1);
        
        if ($phyTeamKey) $team['phy_team_key'] = $phyTeamKey;
        else             $team['phy_team_key'] = $teamName;

        
        return $team;
    }
    /* =========================================================================
     * Generate list of sch teams
     * Physical teams should be called first
     */
    public function getSchTeams()
    {
        if ($this->schTeams) return $this->schTeams;
        $this->schTeams = array();
        
        $games = $this->getGames();
        $teams = array();
        foreach($games as $game)
        {
            $team = $this->getSchTeam($game['game_div'],$game['game_bracket'],$game['home_name']);
            if ($team) $teams[$team['key']] = $team;
            
            $team = $this->getSchTeam($game['game_div'],$game['game_bracket'],$game['away_name']);
            if ($team) $teams[$team['key']] = $team;
        }
        // asort($teams);
        $this->schTeams = $teams;
        return $teams;
    }
    /* ======================================================================
     * Extract individual physical team
     */
    protected function getPhyTeam($div,$brac,$teamName)
    {
        switch($brac)
        {
            case 'FINAL':
            case 'SF':
                return null;
                
            case 'Brac 1':
            case 'Brac 2':
            case 'Brac 3':
            case 'NA':
                break;
            
            default:
                die('Bracket ' . $brac . "\n");
        }
        $team = array();
        
        $team['name']   = $teamName;
        $team['div']    = $div;
        $team['age']    = substr($div,0,3);
        $team['gender'] = substr($div,3,1);
        
        $info = explode('-',$teamName);
        
        $team['region'] = sprintf('R%04u',(int)trim($info[1]));
        $team['coach']  = trim($info[0]);
        
        $team['key']  = sprintf('%s-%s %s',$team['region'],$team['div'],$team['coach']);
        $team['pool'] = null;
        
        return $team;
    }
    /* =========================================================================
     * Extract all physical teams
     */
    public function getPhyTeams()
    {
        if ($this->phyTeams) return $this->phyTeams;
        $this->phyTeams = array();
        
        $games = $this->getGames();
        $teams = array();
        foreach($games as $game)
        {
            $team = $this->getPhyTeam($game['game_div'],$game['game_bracket'],$game['home_name']);
            if ($team) $teams[$team['key']] = $team;
            
            $team = $this->getPhyTeam($game['game_div'],$game['game_bracket'],$game['away_name']);
            if ($team) $teams[$team['key']] = $team;
        }
        // asort($teams);
        $this->phyTeams = $teams;
        return $teams;
    }
    /* =========================================================================
     * Query for the games
     */
    public function getGames()
    {
        if ($this->games) return $this->games;
        $this->games = array();
        
        $sql = 'SELECT * FROM s5games.games ORDER BY game_div,game_date,game_time';

        $db = $this->manager->getEntityManager()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $gamesx = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $games = array();
        
        foreach($gamesx as $game)
        {
            $game['game_dow'] = $game['game_date'];
            switch($game['game_date'])
            {
                case 'FRI': $game['game_date'] = '20110617'; break;
                case 'SAT': $game['game_date'] = '20110618'; break;
                case 'SUN': $game['game_date'] = '20110619'; break;
            }
            $time = $game['game_time'];
            $game['game_time'] = substr($time,0,2) . ':' . substr($time,2,2);
            
            $games[] = $game;
        }
        $this->games = $games;
        return $this->games;
        
        Debug::dump($games[0]);
        die('Game count: ' . count($games));
    }
}

?>
