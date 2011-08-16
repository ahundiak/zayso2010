<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventTeamType
 */
class EventTeamType
{
    /**
     * @var integer $eventTeamTypeId
     */
    private $eventTeamTypeId;

    /**
     * @var string $descx
     */
    private $descx;


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
     * Set descx
     *
     * @param string $descx
     */
    public function setDescx($descx)
    {
        $this->descx = $descx;
    }

    /**
     * Get descx
     *
     * @return string 
     */
    public function getDescx()
    {
        return $this->descx;
    }
}