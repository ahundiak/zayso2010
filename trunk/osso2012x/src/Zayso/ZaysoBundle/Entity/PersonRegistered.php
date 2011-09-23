<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_registered")
 * @ORM\HasLifecycleCallbacks
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

  /** @ORM\Column(type="text",name="datax") */
  protected $datax = '';
  protected $data = array();

  /** @ORM\PrePersist */
  public function onPrePersist() { $this->datax = serialize($this->data); }

  /** @ORM\PreUpdate */
  public function onPreUpdate() { $this->datax = serialize($this->data); }

  /** @ORM\PostLoad */
  public function onLoad() { $this->data = unserialize($this->datax); }

  public function get($name,$default = null)
  {
      if (isset($this->data[$name])) return $this->data[$name];
      return $default;
  }
  public function set($name,$value)
  {
      if ($value === null)
      {
          if (isset($this->data[$name])) unset($this->data[$name]);
          $this->datax = null;
          return;
      }
      $this->data[$name] = $value;
      $this->datax = null;
  }

  public function setPerson($person)
  {
    $this->person = $person;
    if ($person) $person->addRegisteredPerson($this);
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

    /**
     * Set datax
     *
     * @param text $datax
     */
    public function setDatax($datax)
    {
        $this->datax = $datax;
    }

    /**
     * Get datax
     *
     * @return text 
     */
    public function getDatax()
    {
        return $this->datax;
    }
}