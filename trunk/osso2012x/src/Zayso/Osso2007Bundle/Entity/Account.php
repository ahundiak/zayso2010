<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Account
 */
class Account
{
    /**
     * @var integer $accountId
     */
    private $accountId;

    /**
     * @var string $accountUser
     */
    private $accountUser;

    /**
     * @var string $accountPass
     */
    private $accountPass;

    /**
     * @var string $accountName
     */
    private $accountName;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var integer $status
     */
    private $status;


    /**
     * Get accountId
     *
     * @return integer 
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

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
     * @var Zayso\Osso2007Bundle\Entity\Member
     */
    private $members;

    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add members
     *
     * @param Zayso\Osso2007Bundle\Entity\Member $members
     */
    public function addMembers(\Zayso\Osso2007Bundle\Entity\Member $members)
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