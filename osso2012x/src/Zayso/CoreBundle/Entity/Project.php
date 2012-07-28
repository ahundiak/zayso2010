<?php

namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
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

    /** @ORM\Column(type="string",name="desc1",length=80,nullable=true) */
    protected $description = null;

    /** @ORM\Column(type="string",name="status",length=20) */
    protected $status = 'Active';

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent = null;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectGroup")
     * @ORM\JoinColumn(name="project_group_id", referencedColumnName="id")
     */
    protected $projectGroup = null;

    // Getters/setters
    public function setProjectGroup($group) { $this->projectGroup = $group; }
    public function getProjectGroup()       { return $this->projectGroup; }

    public function setParent($project) { $this->parent = $project; }
    public function getParent()         { return $this->parent; }

    public function setId($id) { $this->id = $id;  }
    public function getId()    { return $this->id; }

    public function setDescription($description) { $this->description = $description; }
    public function getDescription()             { return $this->description; }

    public function setStatus($status) { $this->status = $status; }
    public function getStatus()        { return $this->status; }

}