<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Unit
 */
class Unit
{
    /**
     * @var integer $unitId
     */
    private $unitId;

    /**
     * @var integer $unitTypeId
     */
    private $unitTypeId;

    /**
     * @var integer $parentId
     */
    private $parentId;

    /**
     * @var string $keyx
     */
    private $keyx;

    /**
     * @var string $sortx
     */
    private $sortx;

    /**
     * @var string $descPick
     */
    private $descPick;

    /**
     * @var string $prefix
     */
    private $prefix;

    /**
     * @var string $descLong
     */
    private $descLong;


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
     * Set unitTypeId
     *
     * @param integer $unitTypeId
     */
    public function setUnitTypeId($unitTypeId)
    {
        $this->unitTypeId = $unitTypeId;
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

    /**
     * Set parentId
     *
     * @param integer $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
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
     * Set descPick
     *
     * @param string $descPick
     */
    public function setDescPick($descPick)
    {
        $this->descPick = $descPick;
    }

    /**
     * Get descPick
     *
     * @return string 
     */
    public function getDescPick()
    {
        return $this->descPick;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Get prefix
     *
     * @return string 
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set descLong
     *
     * @param string $descLong
     */
    public function setDescLong($descLong)
    {
        $this->descLong = $descLong;
    }

    /**
     * Get descLong
     *
     * @return string 
     */
    public function getDescLong()
    {
        return $this->descLong;
    }
}