<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Openid
 *
 * @ORM\Table(name="account_openid")
 * @ORM\Entity
 */
class AccountOpenid
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="identifier", type="string", length=120, unique=true, nullable=false)
     *
     * openid - identifier
     */
    private $identifier = null;

    /**
     * @ORM\Column(name="provider", type="string", length=80, nullable=true)
     *
     * openid - providerName
     */
    private $provider = '';

    /**
     * @ORM\ManyToOne(targetEntity="AccountPerson")
     * @ORM\JoinColumn(name="account_person_id", referencedColumnName="id", nullable=false)
     */
    protected $accountPerson = null;

    /**
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status = 'Active';

    /**
     * @var string $displayName
     *
     * @ORM\Column(name="display_name", type="string", length=80, nullable=true)
     *
     * openid - displayName
     */
    private $displayName = '';

    /**
     * @var string $userName
     *
     * @ORM\Column(name="user_name", type="string", length=80, nullable=true)
     *
     * openid - preferredUsername
     */
    private $userName = '';

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     *
     * openid - verifiedEmail
     */
    private $email = '';

    public function setAccountPerson($member) { $this->accountPerson = $member; }

    public function getAccountPerson() { return $this->accountPerson; }

    // Loads some stuff from return profile
    public function setProfile($data)
    {
        if (isset($data['identifier']))        $this->identifier  = $data['identifier'];
        if (isset($data['providerName']))      $this->provider    = $data['providerName'];
        if (isset($data['displayName']))       $this->displayName = $data['displayName'];
        if (isset($data['preferredUsername'])) $this->userName    = $data['preferredUsername'];
        if (isset($data['verifiedEmail']))     $this->email       = $data['verifiedEmail'];
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}