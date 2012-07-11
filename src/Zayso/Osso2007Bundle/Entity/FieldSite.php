<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\FieldSite
 *
 * @ORM\Table(name="field_site")
 * @ORM\Entity
 */
class FieldSite
{
    /**
     * @var integer $fieldSiteId
     *
     * @ORM\Column(name="field_site_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fieldSiteId;

    /**
     * @var integer $unitId
     *
     * @ORM\Column(name="unit_id", type="integer", nullable=true)
     */
    private $unitId;

    /**
     * @var string $keyx
     *
     * @ORM\Column(name="keyx", type="string", length=20, nullable=true)
     */
    private $keyx;

    /**
     * @var string $sortx
     *
     * @ORM\Column(name="sortx", type="string", length=10, nullable=true)
     */
    private $sortx;

    /**
     * @var string $descx
     *
     * @ORM\Column(name="descx", type="string", length=20, nullable=true)
     */
    private $descx;



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

    /**
     * Get fieldSiteId
     *
     * @return integer 
     */
    public function getFieldSiteId()
    {
        return $this->fieldSiteId;
    }
}