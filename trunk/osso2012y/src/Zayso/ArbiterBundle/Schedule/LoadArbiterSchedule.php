<?php
namespace Zayso\ArbiterBundle\Schedule;

use Zayso\ArbiterBundle\Entity\Game;

use Zayso\CoreBundle\Component\Debug;

class LoadArbiterSchedule_RowIndexes
{
  public $game,$date,$dow,$time;
  public $sport,$level;
  public $bill,$site,$homeTeam,$awayTeam;
  public $cr,$ar1,$ar2;
  public $homeScore,$awayScore;

  function __construct($header)
  {
    // \Cerad\Debug::dump($header);

    $this->game = array_search('Game',$header);

    $this->date = array_search('Date & Time',$header);
    $this->dow  = $this->date + 1;
    $this->time = $this->date + 2;

    $this->sport = array_search('Sport & Level',$header);
    $this->level = $this->sport + 1;

    $this->bill = array_search('Bill-To',$header);
    $this->site = array_search('Site',$header);
    $this->homeTeam = array_search('Home',$header);
    $this->awayTeam = array_search('Away',$header);

    $this->cr  = array_search('Officials',$header);
    $this->ar1 = $this->cr + 1;
    $this->ar2 = $this->cr + 2;

    $this->homeScore  = array_search('Score(H)',$header);
    $this->awayScore  = array_search('Score(A)',$header);

  }
}
class LoadArbiterSchedule
{
    public function load($csvFileName)
    {
        $games = array();
        
        // Setup to read file
        $file = fopen($csvFileName,'rt');
        $header = fgetcsv($file);
        $rowIndexes = new LoadArbiterSchedule_RowIndexes($header);

        // Insert each record
        while ($line = fgetcsv($file))
        {
            $game = new Game();

            $gameNum = (int)trim($line[$rowIndexes->game]);

            $game->setId     ($gameNum);
            $game->setGameNum($gameNum);

            $game->setDate (trim($line[$rowIndexes->date]));
            $game->setDow  (trim($line[$rowIndexes->dow]));
            $game->setTime (trim($line[$rowIndexes->time]));
            $game->setSport(trim($line[$rowIndexes->sport]));
            $game->setLevel(trim($line[$rowIndexes->level]));
            $game->setBill (trim($line[$rowIndexes->bill]));
            $game->setSite (trim($line[$rowIndexes->site]));

            $game->setHomeTeam(trim($line[$rowIndexes->homeTeam]));
            $game->setAwayTeam(trim($line[$rowIndexes->awayTeam]));

            $game->setCR (trim($line[$rowIndexes->cr]));
            $game->setAR1(trim($line[$rowIndexes->ar1]));
            $game->setAR2(trim($line[$rowIndexes->ar2]));

            if ($rowIndexes->homeScore) $game->setHomeScore((int)trim($line[$rowIndexes->homeScore]));
            if ($rowIndexes->awayScore) $game->setAwayScore((int)trim($line[$rowIndexes->awayScore]));

            if ($gameNum) $games[$gameNum] = $game;
            
            // Debug::dump($game); die('game');
        }
        fclose($file);
        return $games;
    }    
}
?>
