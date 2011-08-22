<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zayso\Osso2007Bundle\Repository\GameRepository as GameRepo;

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
    public function getHeadCoach() { return $this->getPerson(GameRepo::TYPE_HEAD_COACH); }
    public function getAsstCoach() { return $this->getPerson(GameRepo::TYPE_ASST_COACH); }
    public function getManager  () { return $this->getPerson(GameRepo::TYPE_MANAGER); }

    public function getSchTeam() { return $this->schTeam; }

    public function getTeamKey()
    {
        if ($this->schTeam) return $this->schTeam->getTeamKey();
        return null;
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
    private $typeIndex;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId;

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
    private $score;



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
     * Set teamId
     *
     * @param integer $teamId
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * Get teamId
     *
     * @return integer 
     */
    public function getTeamId()
    {
        return $this->teamId;
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