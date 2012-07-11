<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\RegYear
 *
 * @ORM\Table(name="reg_year")
 * @ORM\Entity
 */
class RegYear
{
    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $regYearId;

    /**
     * @var integer $descx
     *
     * @ORM\Column(name="descx", type="integer", nullable=true)
     */
    private $descx;



    /**
     * Set descx
     *
     * @param integer $descx
     */
    public function setDescx($descx)
    {
        $this->descx = $descx;
    }

    /**
     * Get descx
     *
     * @return integer 
     */
    public function getDescx()
    {
        return $this->descx;
    }

    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
    }
}