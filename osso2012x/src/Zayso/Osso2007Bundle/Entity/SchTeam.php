<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zayso\Osso2007Bundle\Repository\GameRepository as GameRepo;

/**
 * Zayso\Osso2007Bundle\Entity\SchTeam
 *
 * @ORM\Table(name="sch_team")
 * @ORM\Entity
 */
class SchTeam
{
    public function getDivisionDesc()
    {
        $divId = $this->getDivisionId();
        return GameRepo::getDivisionDesc($divId);
    }
    public function getRegionKey()
    {
        $regionId = $this->getUnitId();
        return GameRepo::getRegionKey($regionId);
    }
    public function getPerson($type)
    {
        $phyTeam = $this->getPhyTeam();
        if ($phyTeam) return $this->phyTeam->getPerson($type);
    }
    public function getHeadCoach() { return $this->getPerson(Gamerepo::TYPE_HEAD_COACH); }
    public function getAsstCoach() { return $this->getPerson(Gamerepo::TYPE_ASST_COACH); }
    public function getManager  () { return $this->getPerson(Gamerepo::TYPE_MANAGER); }

    public function getId() { return $this->schTeamId; }

    public function getTeamKey() { return $this->descShort; }

    /** ==================================================================================
     * @var integer $schTeamId
     *
     * @ORM\Column(name="sch_team_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $schTeamId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PhyTeam")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="phy_team_id")
     */
    private $phyTeam = null;

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
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     *
     * @ORM\Column(name="season_type_id", type="integer", nullable=true)
     */
    private $seasonTypeId;

    /**
     * @var integer $scheduleTypeId
     *
     * @ORM\Column(name="schedule_type_id", type="integer", nullable=true)
     */
    private $scheduleTypeId;

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
     * @var integer $sortx
     *
     * @ORM\Column(name="sortx", type="integer", nullable=true)
     */
    private $sortx;

    /**
     * @var string $descShort
     *
     * @ORM\Column(name="desc_short", type="string", length=20, nullable=true)
     */
    private $descShort;



    /**
     * Set phyTeam
     *
     * @param $phyTeam
     */
    public function setPhyTeam($phyTeam)
    {
        $this->phyTeam = $phyTeam;
    }

    /**
     * Get phyTeam
     *
     * @return integer 
     */
    public function getPhyTeam()
    {
        return $this->phyTeam;
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

    /**
     * Get schTeamId
     *
     * @return integer 
     */
    public function getSchTeamId()
    {
        return $this->schTeamId;
    }
}