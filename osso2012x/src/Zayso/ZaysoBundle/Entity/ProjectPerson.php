<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** ==================================================
 * @ORM\Entity
 * @ORM\Table(name="project_person")
 * @ORM\HasLifecycleCallbacks
 *
 * In one sense it doesn't really make sense to explicitly link this to the project item
 * Really more of a stand alone item like seqn
 * But maybe not.  Try it this way
 */
class ProjectPerson
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer",name="id")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="Project", inversedBy="persons")
   * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
   */
  protected $project;

  /**
   * @ORM\ManyToOne(targetEntity="Person", inversedBy="projects")
   * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
   */
  protected $person;
  
  /** @ORM\Column(type="text",name="datax") */
  protected $datax = '';

  /** @ORM\Column(type="string",name="status",length=20) */
  protected $status = '';

  protected $data = array();

  /** @ORM\PrePersist */
  public function onPrePersist()
  {
    //die('prePersist');
    $this->datax = serialize($this->data);
  }
  /** @ORM\PreUpdate */
  public function onPreUpdate()
  {
    // die('preUpdate');
    $this->datax = serialize($this->data);
  }
  /** @ORM\PostLoad */
  public function onLoad()
  {
    $this->data = unserialize($this->datax);
  }
  public function __get($name)
  {
    switch($name)
    {
      case 'id':      return $this->id;
      case 'project': return $this->project;
      case 'person':  return $this->person;
      case 'status':  return $this->status;
    }
    if (isset($this->data[$name])) return $this->data[$name];

    return null;
  }
  public function __set($name,$value)
  {
    switch($name)
    {
      case 'id':      $this->id      = $value; return;
      case 'project': $this->project = $value; return;
      case 'person':  $this->person  = $value; return;
      case 'status':  $this->status  = $value; return;
    }
    $this->data[$name] = $value;
    $this->datax = null;
  }
  public function setPerson($person)
  {
    $this->person = $person;
    $person->addProjectPerson($this);
  }
  public function setProject($project)
  {
    $this->project = $project;
    $project->addProjectPerson($this);
  }
   /* ===================================================================
   * Generated Data
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
     * Get project
     *
     * @return Zayso\ZaysoBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
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