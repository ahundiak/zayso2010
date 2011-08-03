<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Zayso\ZaysoBundle\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   *  ORM\GeneratedValue
   */
  protected $id;

  /** @ORM\Column(type="string",name="desc1") */
  protected $desc1 = '';

  /** @ORM\Column(type="string",name="status") */
  protected $status = '';

  /**
   *  @ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="project", cascade={"persist","remove"})
   */
  protected $persons;

  public function __construct()
  {
    $this->persons = new ArrayCollection();
  }
  public function addProjectPerson($person)
  {
    $this->persons[] = $person;
  }

  /* ================================================================================
   * Generated code
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
     * Set desc1
     *
     * @param string $desc1
     */
    public function setDesc1($desc1)
    {
        $this->desc1 = $desc1;
    }

    /**
     * Get desc1
     *
     * @return string 
     */
    public function getDesc1()
    {
        return $this->desc1;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Add persons
     *
     * @param Zayso\ZaysoBundle\Entity\ProjectPerson $persons
     */
    public function addPersons(\Zayso\ZaysoBundle\Entity\ProjectPerson $persons)
    {
        $this->persons[] = $persons;
    }

    /**
     * Get persons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }
}