<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhoneType
 *
 * @ORM\Table(name="phone_type")
 * @ORM\Entity
 */
class PhoneType
{
    /**
     * @var integer $phoneTypeId
     *
     * @ORM\Column(name="phone_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phoneTypeId;

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
     * Get phoneTypeId
     *
     * @return integer 
     */
    public function getPhoneTypeId()
    {
        return $this->phoneTypeId;
    }
}