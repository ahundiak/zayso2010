<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="games")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project = null;

    /** @ORM\Column(type="integer",name="num",nullable=false) */
    protected $num = 0;

    /** @ORM\Column(type="string",name="type",length=20,nullable=true) */
    protected $type = '';

    /** @ORM\Column(type="string",name="datex",length=8,nullable=false) */
    protected $date = '';

    /** @ORM\Column(type="string",name="timex",length=4,nullable=false) */
    protected $time = '';

    /** @ORM\Column(type="integer",name="duration",nullable=true) */
    protected $duration = 0;

    /** @ORM\Column(type="integer",name="field_id",nullable=true) */
    protected $fieldId = 0;

    /** @ORM\Column(type="string",name="field_key",length=20,nullable=true) */
    protected $fieldKey = '';

    /** @ORM\Column(type="string",name="org_key",length=20,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="age",length=8,nullable=false) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=false) */
    protected $gender = '';
    
    /** @ORM\Column(type="string",name="status",length="20",nullable=true) */
    protected $status = '';

    /**
     *  @ORM\OneToMany(targetEntity="GameTeam", mappedBy="game", indexBy="type")
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
        if ($project) $project->addGame($this);
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
     * Set num
     *
     * @param integer $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

    /**
     * Get num
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
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
     * Set date
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set fieldId
     *
     * @param integer $fieldId
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
    }

    /**
     * Get fieldId
     *
     * @return integer 
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Set fieldKey
     *
     * @param string $fieldKey
     */
    public function setFieldKey($fieldKey)
    {
        $this->fieldKey = $fieldKey;
    }

    /**
     * Get fieldKey
     *
     * @return string 
     */
    public function getFieldKey()
    {
        return $this->fieldKey;
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