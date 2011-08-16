<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FieldSite
 */
class FieldSite
{
    /**
     * @var integer $fieldSiteId
     */
    private $fieldSiteId;

    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var string $keyx
     */
    private $keyx;

    /**
     * @var string $sortx
     */
    private $sortx;

    /**
     * @var string $descx
     */
    private $descx;


    /**
     * Get fieldSiteId
     *
     * @return integer 
     */
    public function getFieldSiteId()
    {
        return $this->fieldSiteId;
    }

    /**
     * Set unitId
     *
     * @param integer $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * Get unitId
     *
     * @return integer 
     */
    public function getUnitId()
    {
        return $this->unitId;
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
     * Set sortx
     *
     * @param string $sortx
     */
    public function setSortx($sortx)
    {
        $this->sortx = $sortx;
    }

    /**
     * Get sortx
     *
     * @return string 
     */
    public function getSortx()
    {
        return $this->sortx;
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