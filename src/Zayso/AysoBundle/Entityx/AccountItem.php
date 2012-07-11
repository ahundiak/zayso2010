<?php

namespace Zayso\AysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Zayso\AysoBundle\Repository\AccountRepo")
 * @ORM\Table(name="account")
 */
class AccountItem extends EntityItem
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $_id;

  /** @ORM\Column(name="uname",type="string",length=40,unique=true,nullable=false) */
  protected $_uname = '';

  /** @ORM\Column(name="upass",type="string",length=32,nullable=false) */
  protected $_upass  = '';

  /** @ORM\Column(name="status",type="string",length=16,nullable=false) */
  protected $_status = '';

  /**
   * @ORM\OneToMany(targetEntity="AccountPersonItem", mappedBy="_account", cascade={"persist","delete"})
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

    /**
     * Get _id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set _uname
     *
     * @param string $uname
     */
    public function setUname($uname)
    {
        $this->_uname = $uname;
    }

    /**
     * Get _uname
     *
     * @return string 
     */
    public function getUname()
    {
        return $this->_uname;
    }

    /**
     * Set _upass
     *
     * @param string $upass
     */
    public function setUpass($upass)
    {
        $this->_upass = $upass;
    }

    /**
     * Get _upass
     *
     * @return string 
     */
    public function getUpass()
    {
        return $this->_upass;
    }

    /**
     * Set _status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * Get _status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Add _members
     *
     * @param Zayso\AysoBundle\Entity\AccountPersonItem $members
     */
    public function addMembers(\Zayso\AysoBundle\Entity\AccountPersonItem $members)
    {
        $this->_members[] = $members;
    }

    /**
     * Get _members
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->_members;
    }
}