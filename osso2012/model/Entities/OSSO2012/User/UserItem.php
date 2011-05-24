<?php

namespace OSSO2012\User;

/**
 * @Entity
 * @Table(name="osso2012v.user_view")
 */
class UserItem
{
    /**
     * @Id
     * @Column(type="integer",name="user_id")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string",name="account_uname") */
    private $account_uname;

    /** @Column(type="string",name="person_lname") */
    private $person_lname;

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

    public function getRegYear()      { return $this->vol_reg_year; }

    public function getSafeHavenDesc()    { return $this->cert_type_safe_haven_desc1; }
    public function getCoachBadgeDesc()   { return $this->cert_type_coach_desc1; }
    public function getRefereeBadgeDesc() { return $this->cert_type_referee_desc1; }

    public function isReferee()
    {
      if (!$this->cert_type_referee_desc1) return FALSE;
      return TRUE;
    }
}

?>
