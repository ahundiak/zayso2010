<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventTeam
 */
class EventTeam
{
    /**
     * @var integer $eventTeamId
     */
    private $eventTeamId;

    /**
     * @var integer $eventId
     */
    private $eventId;

    /**
     * @var integer $teamId
     */
    private $teamId;

    /**
     * @var integer $eventTeamTypeId
     */
    private $eventTeamTypeId;

    /**
     * @var integer $typeIndex
     */
    private $typeIndex;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $divisionId
     */
    private $divisionId;

    /**
     * @var integer $score
     */
    private $score;


    /**
     * Get eventTeamId
     *
     * @return integer 
     */
    public function getEventTeamId()
    {
        return $this->eventTeamId;
    }

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
}