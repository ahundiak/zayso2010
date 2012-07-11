<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventPersonType
 *
 * @ORM\Table(name="event_person_type")
 * @ORM\Entity
 */
class EventPersonType
{
    /**
     * @var integer $eventPersonTypeId
     *
     * @ORM\Column(name="event_person_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $eventPersonTypeId;

    /**
     * @var string $keyx
     *
     * @ORM\Column(name="keyx", type="string", length=4, nullable=true)
     */
    private $keyx;

    /**
     * @var string $descx
     *
     * @ORM\Column(name="descx", type="string", length=20, nullable=true)
     */
    private $descx;



    /**
     * Set keyx
     *
     * @param string $keyx
     */
    public function setKeyx($keyx)
    {
        $this->keyx = $keyx;
    }

    /**
     * Get keyx
     *
     * @return string 
     */
    public function getKeyx()
    {
        return $this->keyx;
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

    /**
     * Get eventPersonTypeId
     *
     * @return integer 
     */
    public function getEventPersonTypeId()
    {
        return $this->eventPersonTypeId;
    }
}