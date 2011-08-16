<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Division
 */
class Division
{
    /**
     * @var integer $divisionId
     */
    private $divisionId;

    /**
     * @var integer $sortx
     */
    private $sortx;

    /**
     * @var string $descPick
     */
    private $descPick;

    /**
     * @var string $descLong
     */
    private $descLong;


    /**
     * Get divisionId
     *
     * @return integer 
     */
    public function getDivisionId()
    {
        return $this->divisionId;
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