<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\SchTeam
 */
class SchTeam
{
    /**
     * @var integer $schTeamId
     */
    private $schTeamId;

    /**
     * @var integer $phyTeamId
     */
    private $phyTeamId;

    /**
     * @var integer $projectId
     */
    private $projectId;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     */
    private $seasonTypeId;

    /**
     * @var integer $scheduleTypeId
     */
    private $scheduleTypeId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $divisionId
     */
    private $divisionId;

    /**
     * @var integer $sortx
     */
    private $sortx;

    /**
     * @var string $descShort
     */
    private $descShort;


    /**
     * Get schTeamId
     *
     * @return integer 
     */
    public function getSchTeamId()
    {
        return $this->schTeamId;
    }

    /**
     * Set phyTeamId
     *
     * @param integer $phyTeamId
     */
    public function setPhyTeamId($phyTeamId)
    {
        $this->phyTeamId = $phyTeamId;
    }

    /**
     * Get phyTeamId
     *
     * @return integer 
     */
    public function getPhyTeamId()
    {
        return $this->phyTeamId;
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
     * Set sortx
     *
     * @param integer $sortx
     */
    public function setSortx($sortx)
    {
        $this->sortx = $sortx;
    }

    /**
     * Get sortx
     *
     * @return integer 
     */
    public function getSortx()
    {
        return $this->sortx;
    }

    /**
     * Set descShort
     *
     * @param string $descShort
     */
    public function setDescShort($descShort)
    {
        $this->descShort = $descShort;
    }

    /**
     * Get descShort
     *
     * @return string 
     */
    public function getDescShort()
    {
        return $this->descShort;
    }
}