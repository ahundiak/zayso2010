<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

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

  /** @ORM\Column(name="user_name",type="string",length=40,unique=true,nullable=false)
   *  @Assert\NotBlank()
   */
  protected $userName = '';

  /** @ORM\Column(name="user_pass",type="string",length=32,nullable=false)
   *  @Assert\NotBlank(message="Missing Password")
   */
  protected $userPass  = '';

  /** @ORM\Column(name="status",type="string",length=16,nullable=false) */
  protected $status = '';

  /**
   * @ORM\OneToMany(targetEntity="AccountPerson", mappedBy="account")
   */
  protected $members;

//  public $accountPerson;

  public function __construct()
  {
    $this->members = new ArrayCollection();
  }
  public function addAccountPerson($member)
  {
    $this->members[] = $member;
//    $this->accountPerson = $member;
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
      if ($member->getAccountRelation() == 'Primary') return $member;
    }
    return null;
  }
  public function getPrimaryMemberId()
  {
      $primary = $this->getPrimaryMember();
      if ($primary) return $primary->getId();
      return null;
  }
  public function getPrimaryPersonId()
  {
      $primary = $this->getPrimaryMember();
      if ($primary) return $primary->getPerson()->getId();
      return null;
  }
  public function getPersonForId($personId)
  {
        foreach($this->members as $member)
        {
            $person = $member->getPerson();
            if ($person && $person->getId() == $personId) return $person;
        }
        return null;
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
     * @Assert\NotBlank()
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
        // Not going to allow blank passwords
        if (!$userPass) return;
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
    public function getAccountPersons() { return $this->members; }
}