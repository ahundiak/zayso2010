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

    /** @ORM\Column(type="string",name="desc1") */
    protected $description = '';

    /** @ORM\Column(type="string",name="status") */
    protected $status = '';

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

    /**
     *   ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $persons;

    /**
     *   ORM\OneToMany(targetEntity="PhyTeam", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $phyTeams;

    /**
     *   ORM\OneToMany(targetEntity="SchTeam", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $schTeams;

    /**
     *   ORM\OneToMany(targetEntity="Game", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $games;

    /**
     *   ORM\OneToMany(targetEntity="GameTeam", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $gameTeams;

    public function __construct()
    {
        $this->games     = new ArrayCollection();
        $this->persons   = new ArrayCollection();
        $this->phyTeams  = new ArrayCollection();
        $this->schTeams  = new ArrayCollection();
        $this->gameTeams = new ArrayCollection();
    }
    public function addProjectPerson($person)
    {
        $this->persons[] = $person;
    }
    public function addPhyTeam($phyTeam)
    {
        $this->phyTeams[] = $phyTeam;
    }
    public function addSchTeam($schTeam)
    {
        $this->schTeams[] = $schTeam;
    }
    public function addGame($game)
    {
        $this->games[] = $game;
    }
    public function addGameTeam($gameTeam)
    {
        $this->gameTeams[] = $gameTeam;
    }
    public function setProjectGroup($group) { $this->projectGroup = $group; }
    public function getProjectGroup()       { return $this->projectGroup; }

    public function setParent($project) { $this->parent = $project; }
    public function getParent()         { return $this->parent; }

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
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get desc1
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
}