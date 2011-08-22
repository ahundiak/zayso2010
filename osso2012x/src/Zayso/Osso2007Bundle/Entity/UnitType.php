<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\UnitType
 *
 * @ORM\Table(name="unit_type")
 * @ORM\Entity
 */
class UnitType
{
    /**
     * @var integer $unitTypeId
     *
     * @ORM\Column(name="unit_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $unitTypeId;

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
     * Get unitTypeId
     *
     * @return integer 
     */
    public function getUnitTypeId()
    {
        return $this->unitTypeId;
    }
}