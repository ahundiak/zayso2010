<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\EmailType
 *
 * @ORM\Table(name="email_type")
 * @ORM\Entity
 */
class EmailType
{
    /**
     * @var integer $emailTypeId
     *
     * @ORM\Column(name="email_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emailTypeId;

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
     * Get emailTypeId
     *
     * @return integer 
     */
    public function getEmailTypeId()
    {
        return $this->emailTypeId;
    }
}