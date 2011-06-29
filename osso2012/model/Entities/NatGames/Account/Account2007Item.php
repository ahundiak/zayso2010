<?php

namespace S5Games\Account;

/**
 * @Entity
 * @Table(name="s5gamesv.account2007_view")
 */
class Account2007Item
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  private $_id;

  /** @Column(type="string",name="uname") */
  private $_uname;

  /** @Column(type="string",name="upass") */
  private $_upass;

  /** @Column(type="string",name="lname") */
  private $_lname;

  /** @Column(type="string",name="fname") */
  private $_fname;

  /** @Column(type="string",name="aysoid") */
  private $_aysoid;

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
