<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *  ORM\Entity()
 * @ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\AccountRepository")
 * @ORM\Table(name="account")
 */
class Account
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $id;

  /** @ORM\Column(name="user_name",type="string",length=40,unique=true,nullable=false) */
  protected $userName = '';

  /** @ORM\Column(name="user_pass",type="string",length=32,nullable=false) */
  protected $userPass  = '';

  /** @ORM\Column(name="status",type="string",length=16,nullable=false) */
  protected $status = '';

  /**
   * @ORM\OneToMany(targetEntity="AccountPerson", mappedBy="account", cascade={"persist","remove"})
   */
  protected $members;

  public $accountPerson;

  public function __construct()
  {
    $this->members = new ArrayCollection();
  }
  public function addAccountPerson($member)
  {
    $this->members[] = $member;
    $this->accountPerson = $member;
  }
  public function getMember($memberId)
  {
    foreach($this->members as $member)
    {
      if ($member->getId() == $memberId) return $member;
    }
    return null;
  }
  public function getPrimaryMember()
  {
    foreach($this->members as $member)
    {
      if ($member->getRelId() == 1) return $member;
    }
    return null;
  }
  public function getPrimaryMemberId()
  {
    foreach($this->members as $member)
    {
      if ($member->getRelId() == 1) return $member->getId();
    }
    return 0;
  }
  public function getPrimaryPersonId()
  {
    foreach($this->members as $member)
    {
      if ($member->getRelId() == 1) return $member->getPerson()->getId();
    }
    return 0;
  }
  /* =====================================================================
   * End of custom code
   */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userPass
     *
     * @param string $userPass
     */
    public function setUserPass($userPass)
    {
        $this->userPass = $userPass;
    }

    /**
     * Get userPass
     *
     * @return string 
     */
    public function getUserPass()
    {
        return $this->userPass;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add members
     *
     * @param Zayso\ZaysoBundle\Entity\AccountPerson $members
     */
    public function addMembers(\Zayso\ZaysoBundle\Entity\AccountPerson $members)
    {
        $this->members[] = $members;
    }

    /**
     * Get members
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }
}