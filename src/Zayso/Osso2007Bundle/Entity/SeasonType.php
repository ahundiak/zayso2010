<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\SeasonType
 *
 * @ORM\Table(name="season_type")
 * @ORM\Entity
 */
class SeasonType
{
    /**
     * @var integer $seasonTypeId
     *
     * @ORM\Column(name="season_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $seasonTypeId;

    /**
     * @var string $descx
     *
     * @ORM\Column(name="descx", type="string", length=8, nullable=true)
     */
    private $descx;



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
     * Get seasonTypeId
     *
     * @return integer 
     */
    public function getSeasonTypeId()
    {
        return $this->seasonTypeId;
    }
}