<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="account_openid")
 * @ORM\Entity
 */
class AccountOpenid
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @ORM\Column(name="identifier", type="string", length=120, unique=true, nullable=false)
     */
    private $identifier = null;

    /**
     * @ORM\Column(name="provider", type="string", length=80, nullable=false)
     */
    private $provider = '';

    /**
     *  ORM\ManyToOne(targetEntity="AccountPerson", inversedBy="openids")
     *  ORM\JoinColumn(name="account_person_id", referencedColumnName="id", nullable=false)
     */
  //protected $accountPerson = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="openids")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
     */
    protected $account = null;

    /**
     * @ORM\Column(name="status", type="string", length=20, nullable=false)
     */
    private $status = 'Active';

    /**
     * @ORM\Column(name="display_name", type="string", length=80, nullable=true)
     */
    private $displayName = '';

    /**
     * @ORM\Column(name="user_name", type="string", length=80, nullable=true)
     */
    private $userName = '';

    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email = '';

    public function setAccountPerson($accountPerson)
    {
        $this->accountPerson = $accountPerson;
        if ($accountPerson) $accountPerson->addOpenid($this);
    }

    public function getAccountPerson() { return $this->accountPerson; }
    
    public function setAccount($account) { $this->account = $account; }
    public function getAccount()  { return $this->account; }

    // Loads some stuff from return profile
    public function setProfile($data)
    {
        if (isset($data['identifier']))        $this->identifier  = $data['identifier'];
        if (isset($data['providerName']))      $this->provider    = $data['providerName'];
        if (isset($data['displayName']))       $this->displayName = $data['displayName'];
        if (isset($data['preferredUsername'])) $this->userName    = $data['preferredUsername'];
        if (isset($data['verifiedEmail']))     $this->email       = $data['verifiedEmail'];
    }
    public function setStatus($status) { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setDisplayName($displayName) { $this->displayName = $displayName; }
    public function getDisplayName()      { return $this->displayName; }
    
    public function setUserName($userName) { $this->userName = $userName; }
    public function getUserName()   { return $this->userName; }
    
    public function setEmail($email)  { $this->email = $email; }
    public function getEmail() { return $this->email; }

    public function setProvider($provider) { $this->provider = $provider; }
    public function getProvider()   { return $this->provider; }
    
    public function setIdentifier($identifier) { $this->identifier = $identifier; }
    public function getIdentifier()     { return $this->identifier; }
    
    public function getId()         { return $this->id; }
}