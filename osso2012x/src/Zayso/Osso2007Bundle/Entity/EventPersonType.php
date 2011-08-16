<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EventPersonType
 */
class EventPersonType
{
    /**
     * @var integer $eventPersonTypeId
     */
    private $eventPersonTypeId;

    /**
     * @var string $keyx
     */
    private $keyx;

    /**
     * @var string $descx
     */
    private $descx;


    /**
     * Get eventPersonTypeId
     *
     * @return integer 
     */
    public function getEventPersonTypeId()
    {
        return $this->eventPersonTypeId;
    }

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
}