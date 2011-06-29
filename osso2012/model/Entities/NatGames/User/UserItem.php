<?php

namespace NatGames\User;

/**
 * @Entity
 * @Table(name="natgamesv.user_view")
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

  /** @Column(type="string",name="account_fname") */
  private $account_fname;

  /** @Column(type="string",name="account_lname") */
  private $account_lname;

  /** @Column(type="string",name="account_email") */
  private $account_email;

  /** @Column(type="string",name="account_phonec") */
  private $account_phonec;

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

  public function getPersonFirstName()  { return $this->person_fname;  }
  public function getPersonLastName ()  { return $this->person_lname;  }
  public function getPersonNickName ()  { return $this->person_nname;  }
  public function getPersonEmail()      { return $this->person_email;  }
  public function getPersonCellPhone()  { return $this->person_phonec; }
  public function getPersonDOB()        { return $this->person_dob;    }
  public function getPersonGender()     { return $this->person_gender; }

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
      case '99437977': // Me
      case '94038247': // Mike S
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
