<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\RegYear
 */
class RegYear
{
    /**
     * @var integer $regYearId
     */
    private $regYearId;

    /**
     * @var integer $descx
     */
    private $descx;


    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
    }

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
}