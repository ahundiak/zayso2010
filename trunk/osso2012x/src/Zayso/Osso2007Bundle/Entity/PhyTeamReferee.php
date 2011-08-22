<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeamReferee
 *
 * @ORM\Table(name="phy_team_referee")
 * @ORM\Entity
 */
class PhyTeamReferee
{
    /**
     * @var integer $phyTeamRefereeId
     *
     * @ORM\Column(name="phy_team_referee_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phyTeamRefereeId;

    /**
     * @var integer $phyTeamId
     *
     * @ORM\Column(name="phy_team_id", type="integer", nullable=true)
     */
    private $phyTeamId;

    /**
     * @var integer $refereeId
     *
     * @ORM\Column(name="referee_id", type="integer", nullable=true)
     */
    private $refereeId;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

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
     * @var integer $priRegular
     *
     * @ORM\Column(name="pri_regular", type="integer", nullable=true)
     */
    private $priRegular;

    /**
     * @var integer $priTourn
     *
     * @ORM\Column(name="pri_tourn", type="integer", nullable=true)
     */
    private $priTourn;

    /**
     * @var integer $maxRegular
     *
     * @ORM\Column(name="max_regular", type="integer", nullable=true)
     */
    private $maxRegular;

    /**
     * @var integer $maxTourn
     *
     * @ORM\Column(name="max_tourn", type="integer", nullable=true)
     */
    private $maxTourn;



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

    /**
     * Get phyTeamRefereeId
     *
     * @return integer 
     */
    public function getPhyTeamRefereeId()
    {
        return $this->phyTeamRefereeId;
    }
}