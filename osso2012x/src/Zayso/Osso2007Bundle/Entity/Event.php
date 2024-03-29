<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\Osso2007Bundle\Service\GameManager;

/**
 * Zayso\Osso2007Bundle\Entity\Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Zayso\Osso2007Bundle\Repository\GameRepository")
 */
class Event
{
    /**
     *  @ORM\OneToMany(targetEntity="EventTeam", mappedBy="event", indexBy="eventTeamTypeId", cascade={"persist","remove"})
     */
    private $eventTeams;

    public function getEventTeams() { return $this->eventTeams; }
    public function getGameTeams () { return $this->eventTeams; }

    public function getGameTeamForType($type)
    {
        if (isset($this->eventTeams[$type])) return $this->eventTeams[$type];
        return null;
    }
    public function getGameTeamForId($id)
    {
        foreach($this->eventTeams as $team)
        {
            if ($id == $team->getId()) return $team;
        }
        return null;
    }

    public function getHomeTeam() { return $this->getGameTeamForType(GameManager::TYPE_TEAM_HOME); }
    public function getAwayTeam() { return $this->getGameTeamForType(GameManager::TYPE_TEAM_AWAY); }

    public function addTeam($team)
    {
        if (!$team) return;
        $this->eventTeams[$team->getEventTeamTypeId()] = $team;
    }
    public function getRegionKey()
    {
        return GameManager::getRegionKey($this->unitId);
    }
    public function getFieldId()
    {
        if ($this->field) return $this->field->getFieldId();
        return 0;
    }
    public function getFieldKey()
    {
        if ($this->field) return $this->field->getDescx();
        return null;
    }
    public function getFieldSiteKey()
    {
        if ($this->field)
        {
            $fieldSite = $this->field->getFieldSite();
            if ($fieldSite) return $fieldSite->getDescx();
        }
        return null;
    }
    public function getFieldRegionKey()
    {
        if ($this->field) return $this->field->getRegionKey();
        return null;
    }
    public function setField($field) { $this->field = $field; }
    
    public function __construct()
    {
        $this->eventTeams = new ArrayCollection();
    }

    // Osso2012 Compatibility
    public function getId()   { return $this->eventId;   }
    public function getNum()  { return $this->eventNum;  }
    public function getDate() { return $this->eventDate; }
    public function getTime() { return $this->eventTime; }

    public function setNum ($value) { $this->eventNum  = $value; }
    public function setDate($value) { $this->eventDate = $value; }
    public function setTime($value) { $this->eventTime = $value; }

    public function getEventType()
    {
        switch($this->eventTypeId)
        {
            case 1: return 'Game';
            case 2: return 'Practice';
            case 3: return 'Scrimmage';
            case 4: return 'Jamboree';
            case 5: return 'Unified Practice';
            case 6: return 'Tryouts';
            case 9: return 'Other';
        }
        return 'Unknown';
    }
    public function getSeasonType()
    {
        switch($this->seasonTypeId)
        {
            case 1:  return 'Fall';
            case 2:  return 'Winter';
            case 3:  return 'Spring';
            case 4:  return 'Summer';
        }
        return 'Unknown';
    }
    public function getScheduleType()
    {
        switch($this->scheduleTypeId)
        {
            case 1:  return 'RS';
            case 2:  return 'RT';
            case 3:  return 'AT';
            case 4:  return 'ST';
            case 5:  return 'SG';
            case 6:  return 'NG';
        }
        return 'Unknown';
    }
    public function getEventClass()
    {
        switch($this->scheduleTypeId)
        {
            case 1:  return 'RG';
            case 2:  return 'PP';
            case 3:  return 'QF';
            case 4:  return 'SF';
            case 5:  return 'FF';
            case 6:  return 'CM';
        }
        return 'Unknown';
    }
    public function getRegYear()
    {
        if ($this->regYearId) return $this->regYearId + 2000;
        return 0;
    }
    /** =======================================================================
     * @var integer $eventId
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventId;

    /**
     * @var integer $eventNum
     *
     * @ORM\Column(name="event_num", type="integer", nullable=true)
     */
    private $eventNum = 0;

    /**
     * @var integer $projectId
     *
     * @ORM\Column(name="project_id", type="integer", nullable=true)
     */
    private $projectId;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId = 11;

    /**
     * @var integer $seasonTypeId
     *
     * @ORM\Column(name="season_type_id", type="integer", nullable=true)
     */
    private $seasonTypeId = 1;

    /**
     * @var integer $scheduleTypeId
     *
     * @ORM\Column(name="schedule_type_id", type="integer", nullable=true)
     */
    private $scheduleTypeId = 1;

    /**
     * @var integer $eventTypeId
     *
     * @ORM\Column(name="event_type_id", type="integer", nullable=true)
     */
    private $eventTypeId = 1;

    /**
     * @var integer $classId
     *
     * @ORM\Column(name="class_id", type="integer", nullable=true)
     */
    private $classId = 1;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = 1;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Field")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="field_id")
     */
    private $field = null;

    /**
     * @var string $eventDate
     *
     * @ORM\Column(name="event_date", type="string", length=8, nullable=true)
     */
    private $eventDate;

    /**
     * @var string $eventTime
     *
     * @ORM\Column(name="event_time", type="string", length=4, nullable=true)
     */
    private $eventTime;

    /**
     * @var integer $eventDuration
     *
     * @ORM\Column(name="event_duration", type="integer", nullable=true)
     */
    private $eventDuration = 60;

    /**
     * @var integer $point1
     *
     * @ORM\Column(name="point1", type="integer", nullable=false)
     */
    private $point1 = 1;

    /**
     * @var integer $point2
     *
     * @ORM\Column(name="point2", type="integer", nullable=false)
     */
    private $point2 = 1;



    /**
     * Set eventNum
     *
     * @param integer $eventNum
     */
    public function setEventNum($eventNum)
    {
        $this->eventNum = $eventNum;
    }

    /**
     * Get eventNum
     *
     * @return integer 
     */
    public function getEventNum()
    {
        return $this->eventNum;
    }

    /**
     * Set projectId
     *
     * @param integer $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set regYearId
     *
     * @param integer $regYearId
     */
    public function setRegYearId($regYearId)
    {
        $this->regYearId = $regYearId;
    }

    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
    }

    /**
     * Set seasonTypeId
     *
     * @param integer $seasonTypeId
     */
    public function setSeasonTypeId($seasonTypeId)
    {
        $this->seasonTypeId = $seasonTypeId;
    }

    /**
     * Get seasonTypeId
     *
     * @return integer 
     */
    public function getSeasonTypeId()
    {
        return $this->seasonTypeId;
    }

    /**
     * Set scheduleTypeId
     *
     * @param integer $scheduleTypeId
     */
    public function setScheduleTypeId($scheduleTypeId)
    {
        $this->scheduleTypeId = $scheduleTypeId;
    }

    /**
     * Get scheduleTypeId
     *
     * @return integer 
     */
    public function getScheduleTypeId()
    {
        return $this->scheduleTypeId;
    }

    /**
     * Set eventTypeId
     *
     * @param integer $eventTypeId
     */
    public function setEventTypeId($eventTypeId)
    {
        $this->eventTypeId = $eventTypeId;
    }

    /**
     * Get eventTypeId
     *
     * @return integer 
     */
    public function getEventTypeId()
    {
        return $this->eventTypeId;
    }

    /**
     * Set classId
     *
     * @param integer $classId
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;
    }

    /**
     * Get classId
     *
     * @return integer 
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * Set unitId
     *
     * @param integer $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * Get unitId
     *
     * @return integer 
     */
    public function getUnitId()
    {
        return $this->unitId;
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
     * Set eventDate
     *
     * @param string $eventDate
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
    }

    /**
     * Get eventDate
     *
     * @return string 
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set eventTime
     *
     * @param string $eventTime
     */
    public function setEventTime($eventTime)
    {
        $this->eventTime = $eventTime;
    }

    /**
     * Get eventTime
     *
     * @return string 
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * Set eventDuration
     *
     * @param integer $eventDuration
     */
    public function setEventDuration($eventDuration)
    {
        $this->eventDuration = $eventDuration;
    }

    /**
     * Get eventDuration
     *
     * @return integer 
     */
    public function getEventDuration()
    {
        return $this->eventDuration;
    }

    /**
     * Set point1
     *
     * @param integer $point1
     */
    public function setPoint1($point1)
    {
        $this->point1 = $point1;
    }

    /**
     * Get point1
     *
     * @return integer 
     */
    public function getPoint1()
    {
        return $this->point1;
    }

    /**
     * Set point2
     *
     * @param integer $point2
     */
    public function setPoint2($point2)
    {
        $this->point2 = $point2;
    }

    /**
     * Get point2
     *
     * @return integer 
     */
    public function getPoint2()
    {
        return $this->point2;
    }

    /**
     * Get eventId
     *
     * @return integer 
     */
    public function getEventId()
    {
        return $this->eventId;
    }
}