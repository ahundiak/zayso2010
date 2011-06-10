<?php

namespace S5Games\Game;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="S5Games\Game\GameRepo")
 * @Table(name="s5games.games")
 */
class GameItem
{
  /**
   * @Id
   * @Column(type="integer",name="game_num")
   * @GeneratedValue(strategy="NONE")
   */
  private $id;

  /** @Column(type="string",name="game_date") */
  private $date = '';

  /** @Column(type="string",name="game_time") */
  private $time  = '';
  
  /** @Column(type="string",name="game_div") */
  private $div = '';

  /** @Column(type="string",name="game_field") */
  private $field = '';

  /** @Column(type="string",name="game_type") */
  private $type = '';

  /** @Column(type="string",name="game_bracket") */
  private $bracket = '';

  /** @Column(type="string",name="home_name") */
  private $homeTeam = '';

  /** @Column(type="string",name="away_name") */
  private $awayTeam = '';

  /**
   * @OneToMany(targetEntity="S5Games\Game\GamePersonItem", mappedBy="game", cascade={"persist"})
   */
  private $persons;

  public function __construct()
  {
    $this->persons = new ArrayCollection();
  }

  public function setId   ($value) { $this->id    = $value; }
  public function setNum  ($value) { $this->id    = $value; }

  public function setDate ($value) { $this->date  = $value; }
  public function setTime ($value) { $this->time  = $value; }
  public function setDiv  ($value) { $this->div   = $value; }

  public function setField($value) { $this->field = $value; }
  public function setType ($value) { $this->type  = $value; }

  public function setBracket ($value) { $this->bracket  = $value; }
  public function setHomeTeam($value) { $this->homeTeam = $value; }
  public function setAwayTeam($value) { $this->awayTeam = $value; }

  public function getId()     { return $this->id; }
  public function getNum()    { return $this->id; }

  public function getDate()     { return $this->date;     }
  public function getTime()     { return $this->time;     }
  public function getDiv()      { return $this->div;      }
  public function getField()    { return $this->field;    }
  public function getType()     { return $this->type;     }
  public function getBracket()  { return $this->bracket;  }
  public function getHomeTeam() { return $this->homeTeam; }
  public function getAwayTeam() { return $this->awayTeam; }

  public function getPersons()  { return $this->persons; }
  public function getPerson($posId)
  {
    foreach($this->persons as $person)
    {
      if ($person->getPosId() == $posId) return $person;
    }
    return null;
  }
  public function addPerson($person)
  {
    $this->persons[] = $person;
  }
}
?>
