<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zayso\Osso2007Bundle\Service\GameManager as GameManager;

/**
 * Zayso\Osso2007Bundle\Entity\EventTeam
 *
 * @ORM\Table(name="event_team")
 * @ORM\Entity
 */
class EventTeam
{
    public function getPerson($type)
    {
        $schTeam = $this->getSchTeam();
        if ($schTeam) return $this->schTeam->getPerson($type);
    }
    public function getHeadCoach() { return $this->getPerson(GameManager::TYPE_HEAD_COACH); }
    public function getAsstCoach() { return $this->getPerson(GameManager::TYPE_ASST_COACH); }
    public function getManager  () { return $this->getPerson(GameManager::TYPE_MANAGER); }

    public function getSchTeam() { return $this->schTeam; }

    public function getSchTeamId()
    {
        if ($this->schTeam) return $this->schTeam->getId();
        return null;
    }
    public function getTeamKey()
    {
        if ($this->schTeam) return $this->schTeam->getTeamKey();
        return null;
    }
    public function getRegionKey()
    {
        return GameManager::getRegionKey($this->unitId);
    }
    public function getGenderKey()
    {
        return GameManager::getGenderKey($this->divisionId);
    }
    public function getAgeKey()
    {
        return GameManager::getAgeKey($this->divisionId);
    }
    public function setTeamType($type)
    {
        switch($type)
        {
            case 'Home'  : $this->eventTeamTypeId = 1; return;
            case 'Away'  : $this->eventTeamTypeId = 2; return;
            case 'Away 2': $this->eventTeamTypeId = 3; return;
            case 'Away 3': $this->eventTeamTypeId = 4; return;
        }
    }
    public function getTeamType()
    {
        switch($this->eventTeamTypeId)
        {
            case 1: return 'Home';
            case 2: return 'Away';
            case 3: return 'Away 2';
            case 4: return 'Away 3';
        }
        return 'Unknown';
    }
    public function getId() { return $this->eventTeamId; }

    public function setSchTeam($schTeam) { $this->schTeam = $schTeam; }

    public function setEvent($event)
    {
        $this->event = $event;
        if ($event) $event->addTeam($this);
    }
    
    /** =====================================================================
     * @var integer $eventTeamId
     *
     * @ORM\Column(name="event_team_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventTeamId;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="eventTeams")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="event_id")
     */
    private $event = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SchTeam")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="sch_team_id")
     */
    private $schTeam = null;

    /**
     * @var integer $eventTeamTypeId
     *
     * @ORM\Column(name="event_team_type_id", type="integer", nullable=true)
     */
    private $eventTeamTypeId;

    /**
     * @var integer $typeIndex
     *
     * @ORM\Column(name="type_index", type="integer", nullable=true)
     */
    private $typeIndex = 0;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId = 11;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

    /**
     * @var integer $divisionId
     *
     * @ORM\Column(name="division_id", type="integer", nullable=true)
     */
    private $divisionId;

    /**
     * @var integer $score
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score = 0;



    /**
     * Set eventId
     *
     * @param integer $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
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

    /**
     * Set eventTeamTypeId
     *
     * @param integer $eventTeamTypeId
     */
    public function setEventTeamTypeId($eventTeamTypeId)
    {
        $this->eventTeamTypeId = $eventTeamTypeId;
    }

    /**
     * Get eventTeamTypeId
     *
     * @return integer 
     */
    public function getEventTeamTypeId()
    {
        return $this->eventTeamTypeId;
    }

    /**
     * Set typeIndex
     *
     * @param integer $typeIndex
     */
    public function setTypeIndex($typeIndex)
    {
        $this->typeIndex = $typeIndex;
    }

    /**
     * Get typeIndex
     *
     * @return integer 
     */
    public function getTypeIndex()
    {
        return $this->typeIndex;
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
     * Set divisionId
     *
     * @param integer $divisionId
     */
    public function setDivisionId($divisionId)
    {
        $this->divisionId = $divisionId;
    }

    /**
     * Get divisionId
     *
     * @return integer 
     */
    public function getDivisionId()
    {
        return $this->divisionId;
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
     * Get eventTeamId
     *
     * @return integer 
     */
    public function getEventTeamId()
    {
        return $this->eventTeamId;
    }
}