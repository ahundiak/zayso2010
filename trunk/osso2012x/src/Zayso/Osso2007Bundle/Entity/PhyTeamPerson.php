<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeamPerson
 */
class PhyTeamPerson
{
    /**
     * @var integer $phyTeamPersonId
     */
    private $phyTeamPersonId;

    /**
     * @var integer $phyTeamId
     */
    private $phyTeamId;

    /**
     * @var integer $personId
     */
    private $personId;

    /**
     * @var integer $volTypeId
     */
    private $volTypeId;


    /**
     * Get phyTeamPersonId
     *
     * @return integer 
     */
    public function getPhyTeamPersonId()
    {
        return $this->phyTeamPersonId;
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
     * Set personId
     *
     * @param integer $personId
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get personId
     *
     * @return integer 
     */
    public function getPersonId()
    {
        return $this->personId;
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
}