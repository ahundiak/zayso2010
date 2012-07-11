<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeamPerson
 *
 * @ORM\Table(name="phy_team_person")
 * @ORM\Entity
 */
class PhyTeamPerson
{
    /**
     * @var integer $phyTeamPersonId
     *
     * @ORM\Column(name="phy_team_person_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phyTeamPersonId;

    /**
     * @ORM\ManyToOne(targetEntity="PhyTeam", inversedBy="persons")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="phy_team_id")
     */
    private $phyTeam = null;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    private $person = null;

    /**
     * @var integer $volTypeId
     *
     * @ORM\Column(name="vol_type_id", type="integer", nullable=true)
     */
    private $volTypeId;

    /**
     * Set phyTeamId
     *
     * @param integer $phyTeamId
     */
    public function setPhyTeam($phyTeam)
    {
        $this->phyTeam = $phyTeam;
    }

    /**
     * Get phyTeamId
     *
     * @return integer 
     */
    public function getPhyTeam()
    {
        return $this->phyTeam;
    }

    /**
     * Set personId
     *
     * @param integer $personId
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * Get personId
     *
     * @return integer 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set volTypeId
     *
     * @param integer $volTypeId
     */
    public function setVolTypeId($volTypeId)
    {
        $this->volTypeId = $volTypeId;
    }

    /**
     * Get volTypeId
     *
     * @return integer 
     */
    public function getVolTypeId()
    {
        return $this->volTypeId;
    }

    /**
     * Get phyTeamPersonId
     *
     * @return integer 
     */
    public function getPhyTeamPersonId()
    {
        return $this->phyTeamPersonId;
    }
}