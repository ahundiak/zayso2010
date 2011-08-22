<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventTeamType
 *
 * @ORM\Table(name="event_team_type")
 * @ORM\Entity
 */
class EventTeamType
{
    /**
     * @var integer $eventTeamTypeId
     *
     * @ORM\Column(name="event_team_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventTeamTypeId;

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
     * Get eventTeamTypeId
     *
     * @return integer 
     */
    public function getEventTeamTypeId()
    {
        return $this->eventTeamTypeId;
    }
}