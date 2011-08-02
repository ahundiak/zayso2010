<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_registered")
 */
class PersonRegistered
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="registereds")
   * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $person;

  /** @ORM\Column(type="string",name="reg_type",length=20) */
  protected $regType = '';

  /** @ORM\Column(type="string",name="reg_key",length=40,unique=true) */
  protected $regKey = '';

  /** @ORM\Column(type="string",name="verified",length=20) */
  protected $verified = '';

  public function setPerson($person)
  {
    $this->person = $person;
    $person->addRegisteredPerson($this);
  }

  /* =====================================================================
   * Generated Code
   */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set regType
     *
     * @param string $regType
     */
    public function setRegType($regType)
    {
        $this->regType = $regType;
    }

    /**
     * Get regType
     *
     * @return string 
     */
    public function getRegType()
    {
        return $this->regType;
    }

    /**
     * Set regKey
     *
     * @param string $regKey
     */
    public function setRegKey($regKey)
    {
        $this->regKey = $regKey;
    }

    /**
     * Get regKey
     *
     * @return string 
     */
    public function getRegKey()
    {
        return $this->regKey;
    }

    /**
     * Set verified
     *
     * @param string $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * Get verified
     *
     * @return string 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Get person
     *
     * @return Zayso\ZaysoBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}