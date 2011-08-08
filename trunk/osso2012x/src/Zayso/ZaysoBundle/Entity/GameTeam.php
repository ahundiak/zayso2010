<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="game_team")
 */
class GameTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project = null;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="gameTeams")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    protected $game = null;

    /** @ORM\Column(type="string",name="team_key",length=40,nullable=false) */
    protected $teamKey = '';

    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="age",length=8,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=false) */
    protected $gender = '';
    
    /** @ORM\Column(type="string",name="type",length=20,nullable=true) */
    protected $type = '';

    /** @ORM\Column(type="integer",name="score",nullable=true) */
    protected $score = 0;

    /**
     * @ORM\ManyToOne(targetEntity="SchTeam")
     * @ORM\JoinColumn(name="sch_team_id", referencedColumnName="id")
     */
    protected $schTeam;

    public function __construct()
    {
    }
    public function setProject($project)
    {
        $this->project = $project;
    }
    public function setSchTeam($schTeam)
    {
        $this->schTeam = $schTeam;
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
     * Set score
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
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
     * Set game
     *
     * @param Zayso\ZaysoBundle\Entity\Game $game
     */
    public function setGame(\Zayso\ZaysoBundle\Entity\Game $game)
    {
        $this->game = $game;
    }

    /**
     * Get game
     *
     * @return Zayso\ZaysoBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Get schTeam
     *
     * @return Zayso\ZaysoBundle\Entity\SchTeam 
     */
    public function getSchTeam()
    {
        return $this->schTeam;
    }
}