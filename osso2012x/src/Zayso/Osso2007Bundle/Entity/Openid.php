<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Openid
 */
class Openid
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $accountId
     */
    private $accountId;

    /**
     * @var integer $memberId
     */
    private $memberId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $status
     */
    private $status;

    /**
     * @var string $displayName
     */
    private $displayName;

    /**
     * @var string $userName
     */
    private $userName;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $provider
     */
    private $provider;

    /**
     * @var string $identifier
     */
    private $identifier;


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
     * Set accountId
     *
     * @param integer $accountId
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
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

    /**
     * Set memberId
     *
     * @param integer $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * Get memberId
     *
     * @return integer 
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * Set personId
     *
     * @param integer $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get personId
     *
     * @return integer 
     */
    public function getPersonId()
    {
        return $this->personId;
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
     * Set displayName
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
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
     * Set provider
     *
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get provider
     *
     * @return string 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}