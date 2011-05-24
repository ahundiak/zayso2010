<?php
namespace AYSO\Team;

/**
 * @Entity
 * @Table(schema="ayso", name="ayso.teams")
 */
class TeamItem
{
  /**
   * @Id
   * @Column(type="string",length=20)
   * @GeneratedValue(strategy="NONE")
   */
  private $id;

  /** --------------------------------
   * @Column(type="integer",name="region_id")
   */
  private $regionId;

  /** --------------------------------
   * @Column(type="integer",name="mem_year")
   */
  private $memYear;

  /** --------------------------------
   * @Column(type="string",length=20)
   */
  private $program = '';

  /** --------------------------------
   * @Column(type="string",length=20)
   */
  private $desig = '';

  /** --------------------------------
   * @Column(type="string",length=4)
   */
  private $division = '';

  /** --------------------------------
   * @Column(type="string",length=2)
   */
  private $gender = '';

  /** --------------------------------
   * @Column(type="string",length=20,name="team_name")
   */
  private $name = '';

  /** --------------------------------
   * @Column(type="string",length=20)
   */
  private $colors = '';

  /**
   * @OneToMany(targetEntity="AYSO\Team\TeamPlayerItem", mappedBy="team")
   */
  private $players;

  /* ===========================================================
   * Still a pain
   */
  public function getId()       { return $this->id;       }
  public function getRegionId() { return $this->regionId; }
  public function getMemYear()  { return $this->memYear;  }
  public function getProgram()  { return $this->program;  }
  public function getDesig()    { return $this->desig;    }
  public function getDivision() { return $this->division; }
  public function getGender()   { return $this->gender;   }
  public function getName()     { return $this->name;     }
  public function getColors()   { return $this->colors;   }

  public function setId($value)       { $this->id       = $value; }
  public function setRegionId($value) { $this->regionId = $value; }
  public function setMemYear($value)  { $this->memYear  = $value; }
  public function setProgram($value)  { $this->program  = $value; }
  public function setDesig($value)    { $this->desig    = $value; }
  public function setDivision($value) { $this->division = $value; }
  public function setGender($value)   { $this->gender   = $value; }
  public function setName($value)     { $this->name     = $value; }
  public function setColors($value)   { $this->colors   = $value; }

  public function __construct()
  {
    $this->players   = new \Doctrine\Common\Collections\ArrayCollection();
  }
  public function getPlayers() { return $this->players; }
  public function addPlayer($player) { $this->players[] = $player; }
}
?>
