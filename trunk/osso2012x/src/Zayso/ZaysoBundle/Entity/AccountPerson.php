<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="members")
   * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $person;

  /**
   * @ORM\ManyToOne(targetEntity="Account", inversedBy="members")
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
    $account->addAccountPerson($this);
  }
  public function setPerson($person)
  {
    $this->person = $person;
    $person->addAccountPerson($this);
  }
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