<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FamilyTeam
 */
class FamilyTeam
{
    /**
     * @var integer $familyTeamId
     */
    private $familyTeamId;

    /**
     * @var integer $familyId
     */
    private $familyId;

    /**
     * @var integer $teamId
     */
    private $teamId;

    /**
     * @var integer $familyTeamTypeId
     */
    private $familyTeamTypeId;


    /**
     * Get familyTeamId
     *
     * @return integer 
     */
    public function getFamilyTeamId()
    {
        return $this->familyTeamId;
    }

    /**
     * Set familyId
     *
     * @param integer $familyId
     */
    public function setFamilyId($familyId)
    {
        $this->familyId = $familyId;
    }

    /**
     * Get familyId
     *
     * @return integer 
     */
    public function getFamilyId()
    {
        return $this->familyId;
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
     * Set familyTeamTypeId
     *
     * @param integer $familyTeamTypeId
     */
    public function setFamilyTeamTypeId($familyTeamTypeId)
    {
        $this->familyTeamTypeId = $familyTeamTypeId;
    }

    /**
     * Get familyTeamTypeId
     *
     * @return integer 
     */
    public function getFamilyTeamTypeId()
    {
        return $this->familyTeamTypeId;
    }
}