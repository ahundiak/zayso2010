<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Unit
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity
 */
class Unit
{
    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $unitId;

    /**
     * @var integer $unitTypeId
     *
     * @ORM\Column(name="unit_type_id", type="integer", nullable=true)
     */
    private $unitTypeId;

    /**
     * @var integer $parentId
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var string $keyx
     *
     * @ORM\Column(name="keyx", type="string", length=8, nullable=true)
     */
    private $keyx;

    /**
     * @var string $sortx
     *
     * @ORM\Column(name="sortx", type="string", length=8, nullable=true)
     */
    private $sortx;

    /**
     * @var string $descPick
     *
     * @ORM\Column(name="desc_pick", type="string", length=24, nullable=true)
     */
    private $descPick;

    /**
     * @var string $prefix
     *
     * @ORM\Column(name="prefix", type="string", length=8, nullable=true)
     */
    private $prefix;

    /**
     * @var string $descLong
     *
     * @ORM\Column(name="desc_long", type="string", length=40, nullable=true)
     */
    private $descLong;



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

    /**
     * Get unitId
     *
     * @return integer 
     */
    public function getUnitId()
    {
        return $this->unitId;
    }
}