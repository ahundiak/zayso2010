<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\VolType
 *
 * @ORM\Table(name="vol_type")
 * @ORM\Entity
 */
class VolType
{
    /**
     * @var integer $volTypeId
     *
     * @ORM\Column(name="vol_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $volTypeId;

    /**
     * @var integer $sortx
     *
     * @ORM\Column(name="sortx", type="integer", nullable=true)
     */
    private $sortx;

    /**
     * @var string $keyx
     *
     * @ORM\Column(name="keyx", type="string", length=4, nullable=true)
     */
    private $keyx;

    /**
     * @var string $descx
     *
     * @ORM\Column(name="descx", type="string", length=40, nullable=true)
     */
    private $descx;



    /**
     * Set sortx
     *
     * @param integer $sortx
     */
    public function setSortx($sortx)
    {
        $this->sortx = $sortx;
    }

    /**
     * Get sortx
     *
     * @return integer 
     */
    public function getSortx()
    {
        return $this->sortx;
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

    /**
     * Get volTypeId
     *
     * @return integer 
     */
    public function getVolTypeId()
    {
        return $this->volTypeId;
    }
}