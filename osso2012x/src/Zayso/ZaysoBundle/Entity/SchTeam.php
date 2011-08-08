<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="sch_team")
 */
class SchTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Project") */
    protected $project = null;

    /**
     * @ORM\ManyToOne(targetEntity="PhyTeam")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="id")
     */
    protected $phyTeam = null;

    /** @ORM\Column(type="string",name="team_key",length=40,nullable=false) */
    protected $teamKey = '';

    /** @ORM\Column(type="string",name="team_key2",length=40,nullable=false) */
    protected $teamKey2 = '';

    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="age",length=8,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=false) */
    protected $gender = '';

    /** @ORM\Column(type="string",name="level",length=20,nullable=true) */
    protected $level = '';

    /** @ORM\Column(type="string",name="type",length=20,nullable=true) */
    protected $type = '';

    /**
     *  @ORM\OneToMany(targetEntity="GameTeam", mappedBy="schTeam", fetch="EXTRA_LAZY")
     */
    protected $gameTeams;

    public function __construct()
    {
        $this->gameTeams = new ArrayCollection();
    }
    public function addGameTeam($gameTeam)
    {
        $this->gameTeams[] = $gameTeam;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }
    public function setPhyTeam($phyTeam)
    {
        $this->phyTeam = $phyTeam;
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
     * Set teamKey2
     *
     * @param string $teamKey2
     */
    public function setTeamKey2($teamKey2)
    {
        $this->teamKey2 = $teamKey2;
    }

    /**
     * Get teamKey2
     *
     * @return string 
     */
    public function getTeamKey2()
    {
        return $this->teamKey2;
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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
     * Get phyTeam
     *
     * @return Zayso\ZaysoBundle\Entity\PhyTeam 
     */
    public function getPhyTeam()
    {
        return $this->phyTeam;
    }

    /**
     * Add gameTeams
     *
     * @param Zayso\ZaysoBundle\Entity\GameTeam $gameTeams
     */
    public function addGameTeams(\Zayso\ZaysoBundle\Entity\GameTeam $gameTeams)
    {
        $this->gameTeams[] = $gameTeams;
    }

    /**
     * Get gameTeams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGameTeams()
    {
        return $this->gameTeams;
    }
}