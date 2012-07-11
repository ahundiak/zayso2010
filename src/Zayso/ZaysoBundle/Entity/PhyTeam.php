<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="phy_team")
 */
class PhyTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** * @ORM\ManyToOne(targetEntity="Project") */
    protected $project = null;

    /** @ORM\Column(type="string",name="team_key",length=40,nullable=false) */
    protected $teamKey = '';

    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="age",length=8,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=false) */
    protected $gender = '';

    /** @ORM\Column(type="string",name="level",length=20,nullable=true) */
    protected $level = '';

    /** @ORM\Column(type="string",name="name",length=40,nullable=true) */
    protected $name = '';

    /** @ORM\Column(type="string",name="colors",length=40,nullable=true) */
    protected $colors = '';

    /** @ORM\Column(type="integer",name="eayso_id",nullable=true) */
    protected $eaysoId = 0;

    /** @ORM\Column(type="string",name="eayso_desig",length=20,nullable=true) */
    protected $eaysoDesig = '';
    
    /** @ORM\Column(type="string",name="status",length="20",nullable=true) */
    protected $status = '';

    /**
     *  @ORM\OneToMany(targetEntity="SchTeam", mappedBy="phyTeam", fetch="EXTRA_LAZY" )
     */
    protected $schTeams;

    /**
     *  @ORM\OneToMany(targetEntity="PhyTeamPerson", mappedBy="phyTeam", indexBy="type", fetch="EXTRA_LAZY" )
     */
    protected $persons;

    public function __construct()
    {
        $this->schTeams = new ArrayCollection();
        $this->persons  = new ArrayCollection();
    }
    public function addPerson($person)
    {
        $this->persons[] = $person;
    }
    public function addSchTeam($schTeam)
    {
        $this->schTeams[] = $schTeam;
    }
    public function setProject($project)
    {
        $this->project = $project;
    }
    public function getPersonForType($type)
    {
        if (isset($this->persons[$type])) return $this->persons[$type];
        return null;
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
     * Set teamKey
     *
     * @param string $teamKey
     */
    public function setTeamKey($teamKey)
    {
        $this->teamKey = $teamKey;
    }

    /**
     * Get teamKey
     *
     * @return string 
     */
    public function getTeamKey()
    {
        return $this->teamKey;
    }

    /**
     * Set orgKey
     *
     * @param string $orgKey
     */
    public function setOrgKey($orgKey)
    {
        $this->orgKey = $orgKey;
    }

    /**
     * Get orgKey
     *
     * @return string 
     */
    public function getOrgKey()
    {
        return $this->orgKey;
    }

    /**
     * Set age
     *
     * @param string $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * Get age
     *
     * @return string 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set level
     *
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set colors
     *
     * @param string $colors
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
    }

    /**
     * Get colors
     *
     * @return string 
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set eaysoId
     *
     * @param integer $eaysoId
     */
    public function setEaysoId($eaysoId)
    {
        $this->eaysoId = $eaysoId;
    }

    /**
     * Get eaysoId
     *
     * @return integer 
     */
    public function getEaysoId()
    {
        return $this->eaysoId;
    }

    /**
     * Set eaysoDesig
     *
     * @param string $eaysoDesig
     */
    public function setEaysoDesig($eaysoDesig)
    {
        $this->eaysoDesig = $eaysoDesig;
    }

    /**
     * Get eaysoDesig
     *
     * @return string 
     */
    public function getEaysoDesig()
    {
        return $this->eaysoDesig;
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
     * Get project
     *
     * @return Zayso\ZaysoBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add schTeams
     *
     * @param Zayso\ZaysoBundle\Entity\SchTeam $schTeams
     */
    public function addSchTeams(\Zayso\ZaysoBundle\Entity\SchTeam $schTeams)
    {
        $this->schTeams[] = $schTeams;
    }

    /**
     * Get schTeams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSchTeams()
    {
        return $this->schTeams;
    }

    /**
     * Add persons
     *
     * @param Zayso\ZaysoBundle\Entity\PhyTeamPerson $persons
     */
    public function addPersons(\Zayso\ZaysoBundle\Entity\PhyTeamPerson $persons)
    {
        $this->persons[] = $persons;
    }

    /**
     * Get persons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }
}