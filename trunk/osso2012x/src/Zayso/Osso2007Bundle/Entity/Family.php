<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\Family
 *
 * @ORM\Table(name="family")
 * @ORM\Entity
 */
class Family
{
    /**
     * @var integer $familyId
     *
     * @ORM\Column(name="family_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $familyId;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=true)
     */
    private $name;



    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get familyId
     *
     * @return integer 
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }
}