<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person")
 * @ORM\ChangeTrackingPolicy("NOTIFY") * 
 */
class Person extends BaseEntity
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
    
    /** @ORM\Column(type="string",name="gender",length=1,nullable=true) */
    protected $gender = null;
    
    /** @ORM\Column(type="string",name="dob",length=8,nullable=true) */
    protected $dob = null;

    /** @ORM\Column(type="string",name="email",length=60,nullable=true) */
    protected $email = '';

    /** @ORM\Column(type="string",name="cell_phone",length=20,nullable=true) */
    protected $cellPhone = '';

    /** @ORM\Column(type="string",name="verified",length=20) */
    protected $verified = 'No';

    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = 'Active';
    
    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;
    protected $data  = null;
    
    /**
     *  @ORM\OneToMany(targetEntity="PersonRegistered", mappedBy="person", indexBy="regType")
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
     * @ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="person")
     */
    protected $projectPersons;

    public function clearData()
    {
        $this->data  = null;
        $this->datax = null;
    }
    public function __construct()
    {
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
        // Really should not happen
        if (!$projectPerson) return;
        
        // Filter out those with a null for project
        // Did this because had cascade=persist
        if (!$projectPerson->getProject()) return;
        
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
    
    /* =====================================================================
     * This is called when one and only one project has been loaded
     * In most cases, it will be the current project
     * If one is not found then a fake one is created
     * 
     * It is interesting that the form does not seem to call setCurrentProjectPeron
     * Rather it calls get then sets the project for entity
     */
    protected $currentProjectPersonTemp = null;
    
    public function getCurrentProjectPerson()
    {
        if (count($this->projectPersons) == 1) return $this->projectPersons[0];
        
        if (!$this->currentProjectPersonTemp) 
        {
            $this->currentProjectPersonTemp = new ProjectPerson();
            $this->currentProjectPersonTemp->setPerson($this);
        }
        return $this->currentProjectPersonTemp;
    }
    public function addRegisteredPerson($reg)
    {
        $this->registeredPersons[$reg->getRegType()] = $reg;
    }
    public function getRegisteredPersons() { return $this->registeredPersons; }

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
     * Standard getter/setters
     */
    public function getId       () { return $this->id; }
    public function getDob      () { return $this->dob; }
    public function getEmail    () { return $this->email;   }

    public function getStatus   () { return $this->status; }
    public function getGender   () { return $this->gender; }
    public function getVerified () { return $this->verified;      }
    public function getLastName () { return $this->lastName;  }
    public function getNickName () { return $this->nickName;  }
    public function getFirstName() { return $this->firstName; }
    public function getCellPhone() { return $this->cellPhone; }

    
    public function setDob      ($value) { $this->onScalerPropertySet('dob',      $value); }
    public function setEmail    ($value) { $this->onScalerPropertySet('email',    $value); }
    public function setGender   ($value) { $this->onScalerPropertySet('gender',   $value); }
    public function setStatus   ($value) { $this->onScalerPropertySet('status',   $value); }
    public function setVerified ($value) { $this->onScalerPropertySet('verified', $value); }
    public function setLastName ($value) { $this->onScalerPropertySet('lastName', $value); }
    public function setNickName ($value) { $this->onScalerPropertySet('nickName', $value); }
    public function setFirstName($value) { $this->onScalerPropertySet('firstName',$value); }
    public function setCellPhone($value) { $this->onScalerPropertySet('cellPhone',$value); }

    // ==========================================================
    // Seems to work okay
    protected $regAYSOTemp = null;
    
    public function getRegAYSO()
    {
        if (isset($this->registeredPersons['AYSOV'])) return $this->registeredPersons['AYSOV'];
        
        if ($this->regAYSOTemp) return $this->regAYSOTemp;
        
        $this->regAYSOTemp = new PersonRegistered();
        $this->regAYSOTemp->setRegType('AYSOV');
        $this->regAYSOTemp->setPerson ($this);
        
        return $this->regAYSOTemp;
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
