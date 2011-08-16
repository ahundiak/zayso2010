<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventType
 */
class EventType
{
    /**
     * @var integer $eventTypeId
     */
    private $eventTypeId;

    /**
     * @var string $descx
     */
    private $descx;


    /**
     * Get eventTypeId
     *
     * @return integer 
     */
    public function getEventTypeId()
    {
        return $this->eventTypeId;
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