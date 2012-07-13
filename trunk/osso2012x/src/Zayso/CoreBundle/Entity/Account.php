<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="account",indexes={@ORM\index(name="reset_idx", columns={"reset"})})
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
    protected $status = 'Active';
    
    /** @ORM\Column(name="reset",type="string",length="40",nullable=true)
     */
    protected $reset = null;

    /** @ORM\OneToMany(targetEntity="AccountPerson", mappedBy="account") */
    protected $accountPersons;
    
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=true)
     */
    protected $person;
    
    /** @ORM\OneToMany(targetEntity="AccountOpenid", mappedBy="account") */
    protected $openids;

    /* ==========================================================
     * Custom Code
     */
    public function __construct()
    {
        $this->accountPersons = new ArrayCollection();
        $this->openids        = new ArrayCollection();
    }
    public function addAccountPerson($accountPerson)
    {
        $this->accountPersons[] = $accountPerson;
    }
    public function getAccountPerson($accountPersonId)
    {
        foreach($this->accountPersons as $accountPerson)
        {
            if ($accountPerson->getId() == $accountPersonId) return $accountPerson;
        }
        return null;
    }
    public function getPrimaryAccountPerson()
    {
        foreach($this->accountPersons as $accountPerson)
        {
            if ($accountPerson->isPrimary()) return $accountPerson;

            //if ($accountPerson->getAccountRelation() == 'Primary') return $accountPerson;
        }
        return null;
    }
    public function getAccountPersons() { return $this->accountPersons; }

    /* =====================================================================
     * End of custom code
     */

    public function getId()    { return $this->id; }
    public function setId($id) { $this->id = $id; } // For password reset
    
    public function setUserName($userName) { $this->userName = $userName; }
    public function getUserName()          { return $this->userName; }

    public function setUserPass($userPass) { $this->userPass = $userPass; }
    public function getUserPass()          { return $this->userPass; }

    public function setStatus($status) { $this->status = $status; }
    public function getStatus()        { return $this->status; }
    
    public function setReset($reset) { $this->reset = $reset; }
    public function getReset()       { return $this->reset; }
    
    // Openid stuff
    public function getOpenids() { return $this->openids; }

    public function addOpenid($openid) { $this->openids[] = $openid; }

    public function clearOpenids() { $this->openids = new ArrayCollection(); }
    
    // Should always have a person
    public function setPerson($person) { $this->person = $person; }
    public function getPerson() { return $this->person; }

}