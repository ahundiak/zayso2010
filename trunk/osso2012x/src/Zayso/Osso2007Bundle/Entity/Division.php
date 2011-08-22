<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Division
 *
 * @ORM\Table(name="division")
 * @ORM\Entity
 */
class Division
{
    /**
     * @var integer $divisionId
     *
     * @ORM\Column(name="division_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $divisionId;

    /**
     * @var integer $sortx
     *
     * @ORM\Column(name="sortx", type="integer", nullable=true)
     */
    private $sortx;

    /**
     * @var string $descPick
     *
     * @ORM\Column(name="desc_pick", type="string", length=20, nullable=true)
     */
    private $descPick;

    /**
     * @var string $descLong
     *
     * @ORM\Column(name="desc_long", type="string", length=20, nullable=true)
     */
    private $descLong;



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

    /**
     * Get divisionId
     *
     * @return integer 
     */
    public function getDivisionId()
    {
        return $this->divisionId;
    }
}