<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person")
 * @ORM\HasLifecycleCallbacks
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id = 0;

    /** @ORM\Column(type="string",name="first_name",length=40) */
    protected $firstName = '';

    /** @ORM\Column(type="string",name="last_name",length=40) */
    protected $lastName = '';

    /** @ORM\Column(type="string",name="nick_name",length=40,nullable=true) */
    protected $nickName = '';

    /** @ORM\Column(type="string",name="email",length=60,nullable=true) */
    protected $email = '';

    /** @ORM\Column(type="string",name="cell_phone",length=20,nullable=true) */
    protected $cellPhone = '';

    /** @ORM\Column(type="string",name="verified",length=20) */
    protected $verified = 'No';

    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = 'Active';

    /**
     * @ORM\ManyToOne(targetEntity="Org", cascade={"persist"})
     * @ORM\JoinColumn(name="org_key", referencedColumnName="id", nullable=true)
     */
    protected $org = null;

    /**
     *  @ORM\OneToMany(targetEntity="PersonRegistered", mappedBy="person", indexBy="regType", cascade={"persist"})
     */
    protected $registeredPersons;

    /**
     *  @ORM\OneToMany(targetEntity="PersonPerson", mappedBy="person1", indexBy="relation")
     */
    protected $personPersons;
    
    /**
     *  @ORM\OneToMany(targetEntity="PersonTeamRel", mappedBy="person")
     */
    protected $teamRels;
    
    /**
     *  @ORM\OneToMany(targetEntity="EventPerson", mappedBy="person")
     */
    protected $gameRels;

    public function getGameRels() { return $this->gameRels; }
    
    /**
     * Probably do not need this
     * Need for query to get all people for a given account
     * @ORM\OneToMany(targetEntity="AccountPerson", mappedBy="person")
     */
    protected $accountPersons;
    
    /**
     *  ORM\OneToMany(targetEntity="Account", mappedBy="person")
     */
    // protected $accounts;

    /**
     * @ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="person", cascade={"persist"})
     */
    protected $projectPersons;

    /** @ORM\Column(type="text",name="datax") */
    protected $datax = '';
    protected $data = array();

    /** @ORM\PrePersist */
    public function onPrePersist() { $this->datax = serialize($this->data); }

    /** @ORM\PreUpdate */
    public function onPreUpdate()  { $this->datax = serialize($this->data); }

    /** @ORM\PostLoad */
    public function onLoad()       { $this->data = unserialize($this->datax); }

    public function get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        return null;
    }
    public function set($name,$value)
    {
        if ($value === null)
        {
            if (isset($this->data[$name])) unset($this->data[$name]);
            $this->datax = null;
            return;
        }
        if (isset($this->data[$name]) && $this->data[$name] == $value) return;

        $this->data[$name] = $value;
        $this->datax = null;
    }
  
    public function __construct()
    {
      //$this->accounts          = new ArrayCollection();
        
        $this->registeredPersons = new ArrayCollection();
        $this->personPersons     = new ArrayCollection();
        $this->projectPersons    = new ArrayCollection();
        $this->teamRels          = new ArrayCollection();
        $this->gameRels          = new ArrayCollection();
    }
    public function getPersonPersons() 
    { 
        // Add a sort for Primary Family Peer
        return $this->personPersons; 
    }
    
    public function getGameRelsForProject($projectId)
    {
        $gameRelsx = array();
        foreach($this->gameRels as $gameRel)
        {
            $game = $gameRel->getEvent();
            if ($game)
            {
                if ($game->getProject()->getId() == $projectId) $gameRelsx[] = $gameRel;
            }
        }
        return $gameRelsx;
    }
    public function addProjectPerson($projectPerson)
    {
        // Prevent dups
        /*
        $projectId = $projectPerson->getProject()->getId();
        foreach($this->projectPersons as $projectPerson)
        {
            if ($projectPerson->getProject()->getId() == $projectId) return;
        }*/
        $this->projectPersons[] = $projectPerson;
    }
    public function getProjectPerson($projectId)
    {
        foreach($this->projectPersons as $projectPerson)
        {
            if ($projectPerson->getProject()->getId() == $projectId) return $projectPerson;
        }
        return null;
    }
    public function getProjectPersons() { return $this->projectPersons; }
    public function clearProjectPersons() { $this->projectPersons = new ArrayCollection(); }
    
    public function addRegisteredPerson($reg)
    {
        $this->registeredPersons[$reg->getRegType()] = $reg;
    }
    public function getRegisteredPersons() { return $this->registeredPersons; }

    public function getAysoRegisteredPerson()
    {
        if ($this->registeredPersons['AYSOV']) return $this->registeredPersons['AYSOV'];
        return null;
    }
    public function getAysoid()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRegKey();
        return null;
    }
    public function getRefBadge()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRefBadge();
        return null;
    }
    public function getRefDate()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getRefDate();
        return null;
    }
    public function getSafeHaven()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getSafeHaven();
        return null;
    }
    public function getMemYear()
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) return $rp->getMemYear();
        return null;
    }
    public function setAysoid($aysoid)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRegKey($aysoid);
        return;
    }
    public function setRefBadge($badge)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRefBadge($badge);
        return;
    }
    public function setRefDate($date)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setRefDate($date);
        return;
    }
     public function setSafeHaven($value)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setSafeHaven($value);
        return;
    }
     public function setMemYear($value)
    {
        $rp = $this->getAysoRegisteredPerson();
        if ($rp) $rp->setMemYear($value);
        return;
    }
    public function setDob($dob) { return $this->set('dob',$dob); }
    public function getDob()     { return $this->get('dob'); }
    
    public function setGender($gender) { return $this->set('gender',$gender); }
    public function getGender()        { return $this->get('gender'); }

    public function getPersonName()
    {
        $fname = $this->getFirstName();
        $lname = $this->getLastName();
        $nname = $this->getNickName();

        if ($nname) $name =  $nname . ' ' . $lname;
        else        $name =  $fname . ' ' . $lname;

        return $name;
    }
    /* ======================================================================
     * Generated code follows
     */
    public function getId() { return $this->id; }

    public function setLastName ($name) { $this->lastName  = $name; }
    public function setNickName ($name) { $this->nickName  = $name; }
    public function setFirstName($name) { $this->firstName = $name; }

    public function getLastName () { return $this->lastName;  }
    public function getNickName () { return $this->nickName;  }
    public function getFirstName() { return $this->firstName; }

    public function setEmail($email) { $this->email = $email; }
    public function getEmail()       { return $this->email;   }

    public function setCellPhone($cellPhone) { $this->cellPhone = $cellPhone; }
    public function getCellPhone()           { return $this->cellPhone; }

    public function setVerified($verified)   { $this->verified = $verified; }
    public function getVerified()            { return $this->verified;      }

    public function setStatus($status)       { $this->status = $status; }
    public function getStatus()              { return $this->status; }

    public function setOrg($org) { $this->org = $org; }
    public function getOrg()     { return $this->org; }

    public function getOrgKey()
    {
        if ($this->org) return $this->org->getId();
        return null;
    }
    public function setOrgKey($key)
    {
        //if ($this->org) $this->org->setId($key);
    }
    public function setDatax($datax) { $this->datax = $datax; }
    public function getDatax() { return $this->datax; }
    
    // ==========================================================
    // This might lead to trouble but try it
    
    protected $aysoCertz = null;
    
    public function getAysoCertz()
    {
        if (isset($this->registeredPersons['AYSOV']) && $this->registeredPersons['AYSOV']) return $this->registeredPersons['AYSOV'];
        
        if ($this->aysoCertz) return $this->aysoCertz;
        
        $this->aysoCertz = new PersonRegistered();
        
        return $this->aysoCertz;
    }
    
    protected $orgz = null;
    
    public function getOrgz()
    {
        if ($this->org) return $this->org;
        
        if (!$this->orgz)
        {
            $this->orgz = new Org();
        }
        return $this->orgz;
    }
    /* ========================================================================
     * Team relations
     */
    public function getTeamRels() { return $this->teamRels; }
    
    public function addTeamRel($rel)
    {
        $this->teamRels[$rel->getId()] = $rel;
    }
    public function removeTeamRel($rel)
    {
        unset($this->teamRels[$rel->getId()]);
    }
}
