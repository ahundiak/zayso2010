<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeamReferee
 */
class PhyTeamReferee
{
    /**
     * @var integer $phyTeamRefereeId
     */
    private $phyTeamRefereeId;

    /**
     * @var integer $phyTeamId
     */
    private $phyTeamId;

    /**
     * @var integer $refereeId
     */
    private $refereeId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     */
    private $seasonTypeId;

    /**
     * @var integer $priRegular
     */
    private $priRegular;

    /**
     * @var integer $priTourn
     */
    private $priTourn;

    /**
     * @var integer $maxRegular
     */
    private $maxRegular;

    /**
     * @var integer $maxTourn
     */
    private $maxTourn;


    /**
     * Get phyTeamRefereeId
     *
     * @return integer 
     */
    public function getPhyTeamRefereeId()
    {
        return $this->phyTeamRefereeId;
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
     * Set refereeId
     *
     * @param integer $refereeId
     */
    public function setRefereeId($refereeId)
    {
        $this->refereeId = $refereeId;
    }

    /**
     * Get refereeId
     *
     * @return integer 
     */
    public function getRefereeId()
    {
        return $this->refereeId;
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
     * Set priRegular
     *
     * @param integer $priRegular
     */
    public function setPriRegular($priRegular)
    {
        $this->priRegular = $priRegular;
    }

    /**
     * Get priRegular
     *
     * @return integer 
     */
    public function getPriRegular()
    {
        return $this->priRegular;
    }

    /**
     * Set priTourn
     *
     * @param integer $priTourn
     */
    public function setPriTourn($priTourn)
    {
        $this->priTourn = $priTourn;
    }

    /**
     * Get priTourn
     *
     * @return integer 
     */
    public function getPriTourn()
    {
        return $this->priTourn;
    }

    /**
     * Set maxRegular
     *
     * @param integer $maxRegular
     */
    public function setMaxRegular($maxRegular)
    {
        $this->maxRegular = $maxRegular;
    }

    /**
     * Get maxRegular
     *
     * @return integer 
     */
    public function getMaxRegular()
    {
        return $this->maxRegular;
    }

    /**
     * Set maxTourn
     *
     * @param integer $maxTourn
     */
    public function setMaxTourn($maxTourn)
    {
        $this->maxTourn = $maxTourn;
    }

    /**
     * Get maxTourn
     *
     * @return integer 
     */
    public function getMaxTourn()
    {
        return $this->maxTourn;
    }
}