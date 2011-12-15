<?php

namespace Zayso\ZaysoCoreBundle\Entity;

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
  
  /** @ORM\Column(type="string",name="status",length=20) */
  protected $status = '';

  /** @ORM\Column(type="text",name="datax") */
  protected $datax = '';
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
  public function getPlans()
  {
      $plans = $this->get('plans');
      
      if ($plans) return $plans;
      
      return array(
          'attend'       => 'NS',
          'will_referee' => 'NS',
      );
      
  }
  public function get($name)
  {
      if (isset($this->data[$name])) return $this->data[$name];
      return null;
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
    $person->addProjectPerson($this);
  }
  /* ====================================================================
   * 01 Nov 2011
   * Important not to automatically add project person to project because
   * If a person already exists then swap out the new project person but it stays hanging on the project
   */
  public function setProject($project)
  {
    $this->project = $project;
  //$project->addProjectPerson($this);
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