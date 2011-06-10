<?php

namespace S5Games\Vol;

/**
 * @Entity
 * @Table(name="s5gamesv.vol_view")
 */
class VolItem
{
  /**
   * @Id
   * @Column(type="string",name="aysoid")
   * @GeneratedValue(strategy="NONE")
   */
  private $_aysoid;

  /** @Column(type="string",name="lname") */
  private $_lname;

  /** @Column(type="string",name="fname") */
  private $_fname;

  /** @Column(type="string",name="nname") */
  private $_nname;

  /** @Column(type="string",name="dob") */
  private $_dob;

  /** @Column(type="string",name="gender") */
  private $_gender;

  /** @Column(type="string",name="email") */
  private $_email;

  /** @Column(type="string",name="phonec") */
  private $_phonec;

  /** @Column(type="string",name="org_key") */
  private $_orgKey;

  /** @Column(type="string",name="org_desc") */
  private $_orgDesc;

  /** @Column(type="integer",name="vol_reg_year") */
  private $_volRegYear;

  /** @Column(type="integer",name="cert_coach_type") */
  private $_certCoachType = 0;

  /** @Column(type="integer",name="cert_referee_type") */
  private $_certRefereeType = 0;

  /** @Column(type="integer",name="cert_safe_haven_type") */
  private $_certSafeHavenType = 0;

  /** @Column(type="string",name="cert_coach_desc1") */
  private $_certCoachDesc1 = '';

  /** @Column(type="string",name="cert_referee_desc1") */
  private $_certRefereeDesc1 = '';

  /** @Column(type="string",name="cert_safe_haven_desc1") */
  private $_certSafeHavenDesc1 = '';

  public function getId()           { return $this->id; }

  public function getAccountId       ()  { return $this->account_id; }
  public function getAccountUserName ()  { return $this->account_uname; }
  public function getAccountFirstName()  { return $this->account_fname; }
  public function getAccountLastName ()  { return $this->account_lname; }
  public function getAccountEmail()      { return $this->account_email; }
  public function getAccountCellPhone()  { return $this->account_phonec; }

  public function getAysoid()       { return $this->account_aysoid; }
  public function getVerified()     { return $this->account_verified; }
  
  public function getRegYear()      { return $this->vol_reg_year; }
  
  public function getRegion()      { return $this->person_org_key; }

  public function getSafeHavenDesc()    { return $this->cert_type_safe_haven_desc1; }
  public function getCoachBadgeDesc()   { return $this->cert_type_coach_desc1; }
  public function getRefereeBadgeDesc() { return $this->cert_type_referee_desc1; }

  public function getName()
  {
    if ($this->person_nname) $fname = $this->person_nname;
    else                     $fname = $this->person_fname;

    $lname = $this->person_lname;

    return $fname . ' ' . $lname;
  }
  public function __get($name)
  {
    if ($name[0] == '_') return null;
    $name = '_' . $name;
    return $this->$name;
  }
  public function __set($name,$value)
  {
    if ($name[0] == '_') return;
    $name = '_' . $name;
    $this->$name = $value;
  }
}

?>
