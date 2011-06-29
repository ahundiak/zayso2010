<?php

namespace NatGames\Account;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="NatGames\Account\AccountRepo")
 * @Table(name="natgames.account")
 */
class AccountItem extends \NatGames\EntityItem
{
  /**
   * @Id
   * @Column(type="integer",name="id")
   * @GeneratedValue
   */
  protected $_id;

  /** @Column(type="string",name="uname") */
  protected $_uname = '';

  /** @Column(type="string",name="upass") */
  protected $_upass  = '';

  /** @Column(type="string",name="status") */
  protected $_status = '';

  /**
   * @OneToMany(targetEntity="NatGames\Account\AccountPersonItem", mappedBy="_account", cascade={"persist","delete"})
   */
  protected $_members;

  public function __construct()
  {
    $this->_members = new ArrayCollection();
  }
  public function addAccountPerson($member)
  {
    $this->_members[] = $member;
  }
  public function getMember($memberId)
  {
    foreach($this->members as $member)
    {
      if ($member->id == $memberId) return $member;
    }
    return null;
  }
  public function getPrimaryMember()
  {
    foreach($this->members as $member)
    {
      if ($member->relId == 1) return $member;
    }
    return null;
  }
  public function getPrimaryMemberId()
  {
    foreach($this->members as $member)
    {
      if ($member->relId == 1) return $member->id;
    }
    return 0;
  }
  public function getPrimaryPersonId()
  {
    foreach($this->_members as $member)
    {
      if ($member->relId == 1) return $member->person->id;
    }
    return 0;
  }
}
?>