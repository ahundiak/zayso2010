<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\UnitType
 */
class UnitType
{
    /**
     * @var integer $unitTypeId
     */
    private $unitTypeId;

    /**
     * @var string $descx
     */
    private $descx;


    /**
     * Get unitTypeId
     *
     * @return integer 
     */
    public function getUnitTypeId()
    {
        return $this->unitTypeId;
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