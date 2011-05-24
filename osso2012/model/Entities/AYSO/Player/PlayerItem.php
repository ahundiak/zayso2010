<?php
namespace AYSO\Player;

/**
 * @Entity
 * @Table(schema="ayso", name="ayso.players")
 */
class PlayerItem
{
  /**
   * @Id
   * @Column(type="string",length=20)
   * @GeneratedValue(strategy="NONE")
   */
  private $id;

  /**
   * @Column(type="integer",name="region_id")
   */
  private $regionId;

  /**
   * @Column(type="string",length=20)
   */
  private $fname = '';

  /**
   * @Column(type="string",length=20)
   */
  private $lname = '';

  /**
   * @Column(type="string",length=20)
   */
  private $nname = '';

  /**
   * @Column(type="string",length=20)
   */
  private $mname = '';

  /**
   * @Column(type="string",length=20)
   */
  private $suffix = '';

  /**
   * @Column(type="string",length=20,name="phone_home")
   */
  private $phoneHome = '';

  /**
   * @Column(type="string",length=40)
   */
  private $email = '';

  /**
   * @Column(type="string",length=8)
   */
  private $dob = '';

  /**
   * @Column(type="string",length=2)
   */
  private $gender = '';

  /**
   * @Column(type="string",length=20,name="jersey_size")
   */
  private $jerseySize = '';

  /**
   * @Column(type="integer",name="jersey_number")
   */
  private $jerseyNumber = 0;

  /**
   * @OneToMany(targetEntity="AYSO\Team\TeamPlayerItem", mappedBy="player")
   */
  private $teams;

  // What a pain
  public function getId()           { return $this->id; }
  public function getRegionId()     { return $this->regionId; }
  public function getFirstName()    { return $this->fname; }
  public function getLastName()     { return $this->lname; }
  public function getNickName()     { return $this->nname; }
  public function getMiddleName()   { return $this->mname; }
  public function getSuffix()       { return $this->suffix; }
  public function getPhoneHome()    { return $this->phoneHome; }
  public function getEmail()        { return $this->email; }
  public function getDOB()          { return $this->dob; }
  public function getGender()       { return $this->gender; }
  public function getJerseySize()   { return $this->jerseySize;   }
  public function getJerseyNumber() { return $this->jerseyNumber; }

  public function setId          ($value) { $this->id           = $value; }
  public function setRegionId    ($value) { $this->regionId     = $value; }
  public function setFirstName   ($value) { $this->fname        = $value; }
  public function setLastName    ($value) { $this->lname        = $value; }
  public function setNickName    ($value) { $this->nname        = $value; }
  public function setMiddleName  ($value) { $this->mname        = $value; }
  public function setSuffix      ($value) { $this->suffix       = $value; }
  public function setPhoneHome   ($value) { $this->phoneHome    = $value; }
  public function setEmail       ($value) { $this->email        = $value; }
  public function setDOB         ($value) { $this->dob          = $value; }
  public function setGender      ($value) { $this->gender       = $value; }
  public function setJerseySize  ($value) { $this->jerseySize   = $value; }
  public function setJerseyNumber($value) { $this->jerseyNumber = $value; }

}
?>
