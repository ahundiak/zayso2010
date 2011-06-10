<?php

namespace S5Games\Game;

/**
 * @Entity(repositoryClass="S5Games\Game\GameRepo")
 * @Table(name="s5games.game_person")
 */
class GamePersonItem
{
  /**
   * @Id
   * @Column(type="integer",name="game_person_id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="string",name="aysoid") */
  private $aysoid = '';

  /** @Column(type="string",name="fname") */
  private $fname  = '';
  
  /** @Column(type="string",name="lname") */
  private $lname = '';

  /** @Column(type="integer",name="region") */
  private $region = '';

  /** @Column(type="integer",name="status") */
  private $status = '';

  /**
   * @ManyToOne(targetEntity="S5Games\Game\GameItem", inversedBy="persons")
   * @JoinColumn(name="game_num", referencedColumnName="game_num")
   *
   *  Column(type="integer",name="game_num")
   */
  private $game;

  /** @Column(type="integer",name="pos_id") */
  private $posId = 0;

  /** @Column(type="integer",name="ass_id") */
  private $assId = 0;

  /** @Column(type="string",name="notes") */
  private $notes  = '';
  
  public function setId   ($value) { $this->id    = $value; }

  public function setAysoid   ($value) { $this->aysoid = $value; }

  public function setGame($game)
  {
    $this->game = $game;
    //$this->gameId = $game->getId();
  }

  public function setFirstName($value) { $this->fname  = $value; }
  public function setLastName ($value) { $this->lname  = $value; }

  public function setRegion($value) { $this->region = $value; }
  public function setStatus($value) { $this->status = $value; }

  public function setPosId($value)  { $this->posId = $value; }
  public function setAssId($value)  { $this->assId = $value; }
  public function setNotes($value)  { $this->notes = $value; }

  public function getId()     { return $this->id; }
  public function getAysoid() { return $this->aysoid; }

  public function getName()
  {
    return $this->fname . ' ' . $this->lname;     
  }
  public function getRegion()    { return $this->region;     }
  public function getPosId()     { return $this->posId;      }
  public function getAssId()     { return $this->assId;    }
  public function getStatus()    { return $this->status;     }
  public function getNotes()     { return $this->notes;  }
  public function getFirstName() { return $this->fname; }
  public function getLastName()  { return $this->lname; }

  public function getGame() { return $this->game; }
}
?>
