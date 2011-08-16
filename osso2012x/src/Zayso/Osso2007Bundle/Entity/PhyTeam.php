<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeam
 */
class PhyTeam
{
    /**
     * @var integer $phyTeamId
     */
    private $phyTeamId;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     */
    private $seasonTypeId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $divisionId
     */
    private $divisionId;

    /**
     * @var integer $divisionSeqNum
     */
    private $divisionSeqNum;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $colors
     */
    private $colors;

    /**
     * @var integer $eaysoId
     */
    private $eaysoId;

    /**
     * @var string $eaysoDes
     */
    private $eaysoDes;


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
     * Set divisionSeqNum
     *
     * @param integer $divisionSeqNum
     */
    public function setDivisionSeqNum($divisionSeqNum)
    {
        $this->divisionSeqNum = $divisionSeqNum;
    }

    /**
     * Get divisionSeqNum
     *
     * @return integer 
     */
    public function getDivisionSeqNum()
    {
        return $this->divisionSeqNum;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set colors
     *
     * @param string $colors
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
    }

    /**
     * Get colors
     *
     * @return string 
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set eaysoId
     *
     * @param integer $eaysoId
     */
    public function setEaysoId($eaysoId)
    {
        $this->eaysoId = $eaysoId;
    }

    /**
     * Get eaysoId
     *
     * @return integer 
     */
    public function getEaysoId()
    {
        return $this->eaysoId;
    }

    /**
     * Set eaysoDes
     *
     * @param string $eaysoDes
     */
    public function setEaysoDes($eaysoDes)
    {
        $this->eaysoDes = $eaysoDes;
    }

    /**
     * Get eaysoDes
     *
     * @return string 
     */
    public function getEaysoDes()
    {
        return $this->eaysoDes;
    }
}