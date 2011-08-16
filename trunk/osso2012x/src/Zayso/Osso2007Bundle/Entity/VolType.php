<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\VolType
 */
class VolType
{
    /**
     * @var integer $volTypeId
     */
    private $volTypeId;

    /**
     * @var integer $sortx
     */
    private $sortx;

    /**
     * @var string $keyx
     */
    private $keyx;

    /**
     * @var string $descx
     */
    private $descx;


    /**
     * Get volTypeId
     *
     * @return integer 
     */
    public function getVolTypeId()
    {
        return $this->volTypeId;
    }

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
}