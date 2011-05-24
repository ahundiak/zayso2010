<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Arbiter;

use Doctrine\ORM\EntityRepository;
use PDO;

/**
 * @Entity(repositoryClass="Arbiter\GameRepo")
 * @Table(name="arbiter.games")
 */
class GameItem
{
    /**
     * @Id
     * @Column(type="integer",name="id")
     */
     //@GeneratedValue
    
    private $id;

    /** @Column(type="integer",name="game_num") */
    private $gameNum;

    /** @Column(type="string",name="datex") */
    private $date;

    /** @Column(type="string",name="dow") */
    private $dow;

    /** @Column(type="string",name="timex") */
    private $time;

    /** @Column(type="string",name="sport") */
    private $sport; // MSSL

    /** @Column(type="string",name="levelx") */
    private $level; // MS-B

    /** @Column(type="string",name="bill") */
    private $bill;

    /** @Column(type="string",name="site") */
    private $site;

    /** @Column(type="string",name="home_team") */
    private $homeTeam;

    /** @Column(type="string",name="away_team") */
    private $awayTeam;

    /** @Column(type="string",name="cr") */
    private $cr;

    /** @Column(type="string",name="ar1") */
    private $ar1;

    /** @Column(type="string",name="ar2") */
    private $ar2;

    /** @Column(type="integer",name="home_score") */
    private $homeScore;

    /** @Column(type="integer",name="away_score") */
    private $awayScore;

    public function getId()     { return $this->id; }


    public function setId($value)        { $this->id = $value; }

    public function setGameNum($value)   { $this->gameNum = $value; }

    public function setDate($value)      { $this->date  = $value; }
    public function setDow($value)       { $this->dow   = $value; }
    public function setTime($value)      { $this->time  = $value; }
    public function setSport($value)     { $this->sport = $value; }
    public function setLevel($value)     { $this->level = $value; }
    public function setBill($value)      { $this->bill  = $value; }
    public function setSite($value)      { $this->site  = $value; }
    public function setHomeTeam($value)  { $this->homeTeam = $value; }
    public function setAwayTeam($value)  { $this->awayTeam = $value; }
    public function setHomeScore($value) { $this->homeScore = $value; }
    public function setAwayScore($value) { $this->awayScore = $value; }
    public function setCR($value)        { $this->cr  = $value; }
    public function setAR1($value)       { $this->ar1 = $value; }
    public function setAR2($value)       { $this->ar2 = $value; }

    public function getGameNum()   { return $this->gameNum; }
    public function getDate()      { return $this->date; }
    public function getDow()       { return $this->dow; }
    public function getTime()      { return $this->time; }
    public function getSport()     { return $this->sport; }
    public function getLevel()     { return $this->level; }
    public function getBill()      { return $this->bill; }
    public function getSite()      { return $this->site; }
    public function getHomeTeam()  { return $this->homeTeam; }
    public function getAwayTeam()  { return $this->awayTeam; }
    public function getHomeScore() { return $this->homeScore; }
    public function getAwayScore() { return $this->awayScore; }
    public function getCR()        { return $this->cr; }
    public function getAR1()       { return $this->ar1; }
    public function getAR2()       { return $this->ar2; }
    
}
class GameRepo extends EntityRepository
{
  function getGames($params)
  {
    $dql = 'SELECT game FROM Arbiter\GameItem game';
    $query = $this->_em->createQuery($dql);
    $games = $query->getResult();
    return $games;
    
  }
  function getTeams()
  {
    $conn = $this->_em->getConnection();
    $sql = <<<EOT
SELECT DISTINCT CONCAT(levelx,',',home_team) AS team FROM arbiter.games
UNION  DISTINCT
SELECT DISTINCT CONCAT(levelx,',',away_team) AS team FROM arbiter.games
ORDER BY team;
EOT;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $teams = array();
    while(($team = $stmt->fetchColumn(0)) !== FALSE)
    {
      if ($team) $teams[$team] = $team;
    }
    return $teams;
  }
  function getReferees()
  {
    $conn = $this->_em->getConnection();
    $sql = <<<EOT
SELECT DISTINCT cr AS name FROM arbiter.games
UNION  DISTINCT
SELECT DISTINCT ar1 AS name FROM arbiter.games
UNION  DISTINCT
SELECT DISTINCT ar2 AS name FROM arbiter.games
ORDER BY name;
EOT;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $referees = array();
    while(($name = $stmt->fetchColumn(0)) !== FALSE)
    {
      if ($name) $referees[$name] = $name;
    }
    // $referees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $referees;
  }
}
?>
