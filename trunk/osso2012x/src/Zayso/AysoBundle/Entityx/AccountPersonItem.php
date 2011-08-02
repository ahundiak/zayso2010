<?php

namespace Zayso\AysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_person")
 */
class AccountPersonItem extends EntityItem
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $_id;

  /** @ORM\Column(type="integer",name="rel_id") */
  protected $_relId;
  
  /**
   *  ORM\ManyToOne(targetEntity="NatGames\Person\PersonItem", inversedBy="_members")
   *  ORM\JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $_person;

  /**
   * @ORM\ManyToOne(targetEntity="AccountItem", inversedBy="_members")
   * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
   */
  protected $_account;

  /** @ORM\Column(name="verified",type="string",length=16,nullable=false) */
  protected $_verified = '';

  /** @ORM\Column(name="upass",type="string",length=16,nullable=false) */
  protected $_status = '';

  public function __set($name,$value)
  {
    switch($name)
    {
      case 'account': return $this->setAccount($value); break;
    }
    return parent::__set($name,$value);
  }
  public function setAccount($account)
  {
    $this->_account = $account;
    $account->addAccountPerson($this);
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
     * Set _relId
     *
     * @param integer $relId
     */
    public function setRelId($relId)
    {
        $this->_relId = $relId;
    }

    /**
     * Get _relId
     *
     * @return integer 
     */
    public function getRelId()
    {
        return $this->_relId;
    }

    /**
     * Set _verified
     *
     * @param string $verified
     */
    public function setVerified($verified)
    {
        $this->_verified = $verified;
    }

    /**
     * Get _verified
     *
     * @return string 
     */
    public function getVerified()
    {
        return $this->_verified;
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
     * Get _account
     *
     * @return Zayso\AysoBundle\Entity\AccountItem 
     */
    public function getAccount()
    {
        return $this->_account;
    }
}