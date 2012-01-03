<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="project_field",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="project_key1", columns={"project_id", "key1"})
   })
 */
class ProjectField
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;
  
    /** @ORM\Column(type="string",name="key1",nullable=true) */
    protected $key1 = null;
  
    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = 'Active';

    public function getId     () { return $this->id;      }
    public function getKey    () { return $this->key1;    }
    public function getDesc   () { return $this->key1;    }
    public function getStatus () { return $this->status;  }
    public function getProject() { return $this->project; }

    public function setKey    ($value) { $this->key1    = $value; }
    public function setPool   ($value) { $this->pool    = $value; }
    public function setStatus ($value) { $this->status  = $value; }
    public function setProject($value) { $this->project = $value; }
}