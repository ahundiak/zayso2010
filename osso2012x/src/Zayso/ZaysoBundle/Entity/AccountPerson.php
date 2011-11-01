<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_person")
 */
class AccountPerson
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $id;

  /** @ORM\Column(type="integer",name="rel_id") */
  protected $relId;
  
  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="members", cascade={"persist"})
   * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $person;

  /**
   * @ORM\ManyToOne(targetEntity="Account", inversedBy="members", cascade={"persist"})
   * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
   */
  protected $account;

  /** @ORM\Column(name="verified",type="string",length=16,nullable=false) */
  protected $verified = '';

  /** @ORM\Column(name="status",type="string",length=16,nullable=false) */
  protected $status = '';

    public function setAccount($account)
    {
        $this->account = $account;
        if ($account) $account->addAccountPerson($this);
    }
    public function setPerson($person)
    {
        $this->person = $person;
        if ($person) $person->addAccountPerson($this);
    }
    /** @Assert\NotBlank() */
    public function getUserName () { return $this->account->getUserName(); }

    /** @Assert\NotBlank() */
    public function getUserPass () { return $this->account->getUserPass(); }

    /** @Assert\NotBlank() */
    public function getFirstName() { return $this->person->getFirstName(); }

    /** @Assert\NotBlank() */
    public function getLastName()  { return $this->person->getLastName();  }

    public function getNickName()  { return $this->person->getNickName();  }

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(AYSOV)?\d{8}$/",
     *     message="Must be 8-digit number")
     */
    public function getAysoid()    { return $this->person->getAysoid();  }


    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(AYSOR)?\d{4}$/",
     *     message="Must be 4-digit number")
     */
    public function getRegion()    { return $this->person->getOrgKey();  }

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public function getEmail()     { return $this->person->getEmail();   }
    public function getDob()       { return $this->person->getDob();     }
    public function getGender()    { return $this->person->getGender();  }

    public function getCellPhone() { return $this->person->getCellPhone(); }
    public function getRefBadge () { return $this->person->getRefBadge();  }
    public function getRefDate  () { return $this->person->getRefDate();   }
    public function getSafeHaven() { return $this->person->getSafeHaven(); }
    public function getMemYear  () { return $this->person->getMemYear(); }

    public function setUserName ($value) { return $this->account->setUserName($value); }
    public function setUserPass ($value) { return $this->account->setUserPass($value); }
    public function setFirstName($value) { return $this->person->setFirstName($value); }
    public function setLastName ($value) { return $this->person->setLastName ($value); }
    public function setNickName ($value) { return $this->person->setNickName ($value); }
    public function setEmail    ($value) { return $this->person->setEmail    ($value); }
    public function setCellPhone($value) { return $this->person->setCellPhone($value); }
    public function setDob      ($value) { return $this->person->setDob      ($value); }
    public function setGender   ($value) { return $this->person->setGender   ($value); }

    public function setAysoid   ($value) { return $this->person->setAysoid   ($value); }
    public function setRegion   ($value) { return $this->person->setOrgKey   ($value); }
    public function setRefBadge ($value) { return $this->person->setRefBadge ($value); }
    public function setRefDate  ($value) { return $this->person->setRefDate  ($value); }
    public function setSafeHaven($value) { return $this->person->setSafeHaven($value); }
    public function setMemYear  ($value) { return $this->person->setMemYear  ($value); }
    
  /* ===========================================================================
   * Generated code follows
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
     * Set relId
     *
     * @param integer $relId
     */
    public function setRelId($relId)
    {
        $this->relId = $relId;
    }

    /**
     * Get relId
     *
     * @return integer 
     */
    public function getRelId()
    {
        return $this->relId;
    }

    /**
     * Set verified
     *
     * @param string $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * Get verified
     *
     * @return string 
     */
    public function getVerified()
    {
        return $this->verified;
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
     * Get account
     *
     * @return Zayso\ZaysoBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Get person
     *
     * @return Zayso\ZaysoBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}