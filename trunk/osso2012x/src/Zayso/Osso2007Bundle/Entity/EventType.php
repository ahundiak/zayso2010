<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventType
 *
 * @ORM\Table(name="event_type")
 * @ORM\Entity
 */
class EventType
{
    /**
     * @var integer $eventTypeId
     *
     * @ORM\Column(name="event_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventTypeId;

    /**
     * @var string $descx
     *
     * @ORM\Column(name="descx", type="string", length=20, nullable=true)
     */
    private $descx;



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

    /**
     * Get eventTypeId
     *
     * @return integer 
     */
    public function getEventTypeId()
    {
        return $this->eventTypeId;
    }
}