<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FamilyTeam
 *
 * @ORM\Table(name="family_team")
 * @ORM\Entity
 */
class FamilyTeam
{
    /**
     * @var integer $familyTeamId
     *
     * @ORM\Column(name="family_team_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $familyTeamId;

    /**
     * @var integer $familyId
     *
     * @ORM\Column(name="family_id", type="integer", nullable=true)
     */
    private $familyId;

    /**
     * @var integer $teamId
     *
     * @ORM\Column(name="team_id", type="integer", nullable=true)
     */
    private $teamId;

    /**
     * @var integer $familyTeamTypeId
     *
     * @ORM\Column(name="family_team_type_id", type="integer", nullable=true)
     */
    private $familyTeamTypeId;



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

    /**
     * Get familyTeamId
     *
     * @return integer 
     */
    public function getFamilyTeamId()
    {
        return $this->familyTeamId;
    }
}