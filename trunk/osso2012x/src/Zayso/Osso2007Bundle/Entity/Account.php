<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Zayso\Osso2007Bundle\Entity\Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity
 */
class Account
{
    /**
     *  @ORM\OneToMany(targetEntity="Member", mappedBy="account", cascade={"persist","remove"})
     */
    private $members;

    /**
     *  @ORM\OneToMany(targetEntity="Openid", mappedBy="account")
     */
    private $openids;

    public function getOpenids() { return $this->openids; }

    public function getMembers() { return $this->members; }
    public function getPrimaryMember()
    {
        foreach($this->members as $member)
        {
            if ($member->getLevel() == 1) return $member;
        }
        return null;
    }
    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * @var integer $accountId
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $accountId;

    /**
     * @var string $accountUser
     *
     * @ORM\Column(name="account_user", type="string", length=40, nullable=false)
     */
    private $accountUser;

    /**
     * @var string $accountPass
     *
     * @ORM\Column(name="account_pass", type="string", length=32, nullable=false)
     */
    private $accountPass;

    /**
     * @var string $accountName
     *
     * @ORM\Column(name="account_name", type="string", length=40, nullable=false)
     */
    private $accountName;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=40, nullable=true)
     */
    private $email;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;



    /**
     * Set accountUser
     *
     * @param string $accountUser
     */
    public function setAccountUser($accountUser)
    {
        $this->accountUser = $accountUser;
    }

    /**
     * Get accountUser
     *
     * @return string 
     */
    public function getAccountUser()
    {
        return $this->accountUser;
    }

    /**
     * Set accountPass
     *
     * @param string $accountPass
     */
    public function setAccountPass($accountPass)
    {
        $this->accountPass = $accountPass;
    }

    /**
     * Get accountPass
     *
     * @return string 
     */
    public function getAccountPass()
    {
        return $this->accountPass;
    }

    /**
     * Set accountName
     *
     * @param string $accountName
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
    }

    /**
     * Get accountName
     *
     * @return string 
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get accountId
     *
     * @return integer 
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}