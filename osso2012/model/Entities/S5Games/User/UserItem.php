<?php

namespace S5Games\User;

/**
 * @Entity
 * @Table(name="s5gamesv.user_view")
 */
class UserItem
{
  /**
   * @Id
   * @Column(type="integer",name="user_id")
   * @GeneratedValue
   */
  private $id;

  /** @Column(type="integer",name="account_id") */
  private $account_id;

  /** @Column(type="string",name="account_uname") */
  private $account_uname;

  /** @Column(type="string",name="account_aysoid") */
  private $account_aysoid;

  /** @Column(type="string",name="account_verified") */
  private $account_verified;

  /** @Column(type="string",name="person_lname") */
  private $person_lname;

  /** @Column(type="string",name="person_fname") */
  private $person_fname;

  /** @Column(type="string",name="person_nname") */
  private $person_nname;

    /** @Column(type="string",name="person_dob") */
    private $person_dob;

    /** @Column(type="string",name="person_gender") */
    private $person_gender;

    /** @Column(type="string",name="person_email") */
    private $person_email;

    /** @Column(type="string",name="person_phonec") */
    private $person_phonec;

    /** @Column(type="string",name="person_org_key") */
    private $person_org_key;

    /** @Column(type="string",name="person_org_desc") */
    private $person_org_desc;

    /** @Column(type="integer",name="vol_reg_year") */
    private $vol_reg_year;

    /** @Column(type="string",name="cert_type_coach_desc1") */
    private $cert_type_coach_desc1 = '';

    /** @Column(type="string",name="cert_type_referee_desc1") */
    private $cert_type_referee_desc1 = '';

    /** @Column(type="string",name="cert_type_safe_haven_desc1") */
    private $cert_type_safe_haven_desc1 = '';

  public function getId()           { return $this->id; }
  public function getAccountName()  { return $this->account_uname; }

  public function getAysoid()       { return $this->account_aysoid; }
  public function getRegYear()      { return $this->vol_reg_year; }
  
  public function getRegion()      { return $this->person_org_key; }

  public function getSafeHavenDesc()    { return $this->cert_type_safe_haven_desc1; }
  public function getCoachBadgeDesc()   { return $this->cert_type_coach_desc1; }
  public function getRefereeBadgeDesc() { return $this->cert_type_referee_desc1; }

  public function isReferee()
  {
    if (!$this->cert_type_referee_desc1) return FALSE;
    return TRUE;
  }
  public function isGuest()
  {
    return $this->getId() ? false: true;
  }
  public function isSignedIn()
  {
    return $this->getId() ? true: false;
  }
  public function isAdmin()
  {
    switch ($this->account_aysoid)
    {
      case '99437977':
        return true;
    }
    return false;
  }
  public function getName()
  {
    if ($this->person_nname) $fname = $this->person_nname;
    else                     $fname = $this->person_fname;

    $lname = $this->person_lname;

    return $fname . ' ' . $lname;
  }
}

?>
