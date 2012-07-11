<?php
namespace Zayso\ArbiterBundle\Component;

use Zayso\ArbiterBundle\Entity\Game;

use Zayso\CoreBundle\Component\Debug;

class ImportLesSchedule_RowIndexes
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
class ImportLesSchedule
{
    protected $dates  = array('4/21/12'  => '4/21/2012','4/22/12' => '4/22/2012');
    protected $levels = array('U9 Girls' => 'U09 Girls');
    
    protected $sites = array
    (
      'Merrimack 1a Big North #M01N'   => 'Merrimack, 01N',  
      'Merrimack 1b South Small #M01S' => 'Merrimack, 01S',  
      'Merrimack 10 Small #M10S'       => 'Merrimack, 10',  
      'Merrimack 9 Big #M09B'          => 'Merrimack, 09',  
        
      'Merrimack 1 #M01'               => 'Merrimack, 01',  
      'Merrimack 2 #M02'               => 'Merrimack, 02',  
      'Merrimack 3 #M03'               => 'Merrimack, 03',  
      'Merrimack 4 #M04'               => 'Merrimack, 04',  
      'Merrimack 5 #M05'               => 'Merrimack, 05',  
      'Merrimack 6 #M06'               => 'Merrimack, 06',  
      'Merrimack 7 #M07'               => 'Merrimack, 07',  
      'Merrimack 8 #M08'               => 'Merrimack, 08',  
      'Merrimack 9 #M09'               => 'Merrimack, 09',  
   );
    
    public function import($csvFileName)
    {
        $games = array();
        
        // Setup to read file
        $file = fopen($csvFileName,'rt');
        $header = fgetcsv($file);
        $rowIndexes = new ImportLesSchedule_RowIndexes($header);

        // Insert each record
        while ($line = fgetcsv($file))
        {
            $game = new Game();

            $gameNum = (int)trim($line[$rowIndexes->game]);

            $game->setId     ($gameNum + 7000);
            $game->setGameNum($gameNum + 7000);

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
                
                $games[$gameNum+7000] = $game;
            }
            //Debug::dump($game); die('game');
        }
        fclose($file);
        return $games;
    }    
}
?>
