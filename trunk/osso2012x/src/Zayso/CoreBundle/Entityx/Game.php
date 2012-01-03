<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *  ORM\Entity()
 *  ORM\Table(name="game")
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

    /**
     * @ORM\ManyToOne(targetEntity="ProjectField")
     * @ORM\JoinColumn(name="project_field_id", referencedColumnName="id")
     */
    protected $field = null;

    /** @ORM\Column(type="string",name="field_key",length=40,nullable=true) */
    protected $fieldKey = '';
    
    /**
     * @ORM\ManyToOne(targetEntity="GameGroup")
     * @ORM\JoinColumn(name="game_group_id", referencedColumnName="id")
     */
    protected $gameGroup = null;
    
    /** @ORM\Column(type="string",name="group_key",length=40,nullable=true) */
    protected $gameGroupKey = '';

    /** @ORM\Column(type="string",name="org_key",length=40,nullable=true) */
    protected $orgKey = '';

    /** @ORM\Column(type="string",name="age",length=8,nullable=true) */
    protected $age = '';

    /** @ORM\Column(type="string",name="gender",length=8,nullable=true) */
    protected $gender = '';
    
    /** @ORM\Column(type="string",name="status",length="20",nullable=true) */
    protected $status = '';

    /**
     *  @ORM\OneToMany(targetEntity="GameTeam", mappedBy="game", indexBy="type", cascade={"persist","remove"})
     */
    protected $gameTeams;

    /**
     *  @ORM\OneToMany(targetEntity="GamePerson", mappedBy="game", indexBy="type", fetch="EXTRA_LAZY" )
     */
    protected $persons;
    
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
        $this->gameTeams = new ArrayCollection();
        $this->persons   = new ArrayCollection();
    }
    public function addGameTeam($gameTeam)
    {
        $this->gameTeams[$gameTeam->getType()] = $gameTeam;
    }
    public function addPerson($person)
    {
        $this->persons[] = $person;
    }
    public function setProject($project)
    {
        $this->project = $project;
      //if ($project) $project->addGame($this);
    }

    /* ===========================================================================
     * Lots of special processing
     *
     */
    public function getGameTeamForType($type)
    {
        if (isset($this->gameTeams[$type])) return $this->gameTeams[$type];
        return null;
    }
    public function getHomeTeam() { return $this->getGameTeamForType('Home'); }
    public function getAwayTeam() { return $this->getGameTeamForType('Away'); }

    public function getGamePersonForType($type)
    {
        if (isset($this->persons[$type])) return $this->persons[$type];
        return null;
    }
    public function getSchTeamIdForType($type)
    {
        $gameTeam = $this->getGameTeamForType($type);
        if (!$gameTeam) return 0;

        $schTeam = $gameTeam->getSchTeam();
        if (!$schTeam) return 0;

        return $schTeam->getId();
    }
    public function getHomeSchTeamId() { return $this->getSchTeamIdForType('Home'); }
    public function getAwaySchTeamId() { return $this->getSchTeamIdForType('Away'); }

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

    public function setFieldKey($fieldKey) { $this->fieldKey = $fieldKey; }

    public function getFieldKey() { return $this->fieldKey; }
    
    public function setGroupKey($groupKey) { $this->groupKey = $groupKey; }

    public function getGroupKey() { return $this->groupKey; }

    public function setOrgKey($orgKey) { $this->orgKey = $orgKey; }

    public function getOrgKey() { return $this->orgKey; }

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

    /**
     * Add persons
     *
     * @param Zayso\ZaysoBundle\Entity\GamePerson $persons
     */
    public function addPersons(\Zayso\ZaysoBundle\Entity\GamePerson $persons)
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