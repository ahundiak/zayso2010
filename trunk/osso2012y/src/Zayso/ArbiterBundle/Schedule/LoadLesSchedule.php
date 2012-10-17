<?php
namespace Zayso\ArbiterBundle\Schedule;

use Zayso\ArbiterBundle\Entity\Game;

class LoadLesSchedule_RowIndexes
{
  public $game,$date,$dow,$time;
  public $sport,$level;
  public $bill,$site,$homeTeam,$awayTeam;
  public $cr,$ar1,$ar2;
  public $homeScore,$awayScore;

  function __construct($header)
  {
    // \Cerad\Debug::dump($header);

    $this->game = array_search('Game No.',$header);
    $this->date  = array_search('Date',  $header);
    $this->time  = array_search('Start', $header);

    $this->level = array_search('Flight',$header);

    $this->site     = array_search('Field',$header);
    $this->homeTeam = array_search('Home Team Full',$header);
    $this->awayTeam = array_search('Away Team Full',$header);

  }
}
class LoadLesSchedule
{
    protected $dates  = array(
            '10/20/12'  => '10/20/2012',
            '10/21/12'  => '10/21/2012',
            '10/22/12'  => '10/22/2012',
    );
    protected $levels = array(
        'U9 Girls'         => 'U09 Girls',
        
        'U9 Boys'          => 'U09 Boys',
        'U9 Boys Gold'     => 'U09 Boys',
        'U9 Boys Silver'   => 'U09 Boys',
        
        'U10 Girls'        => 'U10 Girls',
        'U10 Boys'         => 'U10 Boys',
        
        'U11 Girls'        => 'U11 Girls',
        'U11 Boys'         => 'U11 Boys',
        'U11 Girls Silver' => 'U11 Girls',
        'U11 Boys Silver'  => 'U11 Boys',        
        'U11 Girls Gold'   => 'U11 Girls',
        'U11 Boys Gold'    => 'U11 Boys',
        
        'U12 Girls'        => 'U12 Girls',
        'U12 Boys'         => 'U12 Boys',
        'U12 Girls Silver' => 'U12 Girls',
        'U12 Boys Silver'  => 'U12 Boys',        
        'U12 Girls Gold'   => 'U12 Girls',
        'U12 Boys Gold'    => 'U12 Boys',
        
        'U13 Girls'        => 'U13 Girls',
        'U13 Boys'         => 'U13 Boys',
        'U14 Girls'        => 'U14 Girls',
        'U14 Boys'         => 'U14 Boys',
        'U15 Girls'        => 'U15 Girls',
        'U15 Boys'         => 'U15 Boys',
        'U16 Girls'        => 'U16 Girls',
        'U16 Boys'         => 'U16 Boys',
        
        'U16/U17 Girls Gold'   => 'U17 Girls',
        'U16/U17 Girls Silver' => 'U17 Girls',
        
        'U17 Boys Gold'   => 'U17 Boys',
        'U17 Boys Silver' => 'U17 Boys',
        
        'U18/U19'         => 'U19 Boys',
        'U18/U19 Girls'   => 'U19 Girls',
        
    );
    
    protected $sites = array
    (
        'Merrimack 1a Big North #M01N'   => 'Merrimack, MM01N',  
        'Merrimack 1b South Small #M01S' => 'Merrimack, MM01S',  
        'Merrimack 10 Small #M10S'       => 'Merrimack, MM10',  
        'Merrimack 10 Big #M10B'         => 'Merrimack, MM10',  
        'Merrimack 9 Big #M09B'          => 'Merrimack, MM09',  
        
        'Merrimack 1 #M01'               => 'Merrimack, MM01',  
        'Merrimack 2 #M02'               => 'Merrimack, MM02',  
        'Merrimack 3 #M03'               => 'Merrimack, MM03',  
        'Merrimack 4 #M04'               => 'Merrimack, MM04',  
        'Merrimack 5 #M05'               => 'Merrimack, MM05',  
        'Merrimack 6 #M06'               => 'Merrimack, MM06',  
        'Merrimack 7 #M07'               => 'Merrimack, MM07',  
        'Merrimack 8 #M08'               => 'Merrimack, MM08',  
        'Merrimack 9 #M09'               => 'Merrimack, MM09',  
      
        'John Hunt 1 #JH1'        => 'John Hunt, JH01',
        'John Hunt 2 #JH2'        => 'John Hunt, JH02',
        'John Hunt 3 #JH3'        => 'John Hunt, JH03',
        'John Hunt 4 #JH4'        => 'John Hunt, JH04',
        'John Hunt 5 Small #JH5S' => 'John Hunt, JH05',
        'John Hunt 6 #JH6'        => 'John Hunt, JH06',
    );
    
    public function load($csvFileName)
    {
        $games = array();
        
        $gameNumOffset = 7000;
       
        // Setup to read file
        $file = fopen($csvFileName,'rt');
        $header = fgetcsv($file);
        $rowIndexes = new LoadLesSchedule_RowIndexes($header);

        // Insert each record
        while ($line = fgetcsv($file))
        {
            $game = new Game();

            $gameNum = (int)trim($line[$rowIndexes->game]);

            $game->setId     ($gameNum + $gameNumOffset);
            $game->setGameNum($gameNum + $gameNumOffset);
            
            $game->setSport('HFC Kicks');
            
            $game->setDate (trim($line[$rowIndexes->date]));
            $game->setTime (trim($line[$rowIndexes->time]));
            $game->setLevel(trim($line[$rowIndexes->level]));
            $game->setSite (trim($line[$rowIndexes->site]));

            $game->setHomeTeam(trim($line[$rowIndexes->homeTeam]));
            $game->setAwayTeam(trim($line[$rowIndexes->awayTeam]));

            if ($gameNum) 
            {
                if (isset($this->sites [$game->getSite ()])) $game->setSite ($this->sites [$game->getSite ()]);
                if (isset($this->dates [$game->getDate ()])) $game->setDate ($this->dates [$game->getDate ()]);
                if (isset($this->levels[$game->getLevel()])) $game->setLevel($this->levels[$game->getLevel()]);
                
                $games[$gameNum + $gameNumOffset] = $game;
            }
        }
        fclose($file);
        return $games;
    }
}
?>
