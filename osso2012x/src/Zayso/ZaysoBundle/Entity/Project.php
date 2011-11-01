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
     *  @ORM\OneToMany(targetEntity="ProjectPerson", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $persons;

    /**
     *  @ORM\OneToMany(targetEntity="PhyTeam", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $phyTeams;

    /**
     *  @ORM\OneToMany(targetEntity="SchTeam", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $schTeams;

    /**
     *  @ORM\OneToMany(targetEntity="Game", mappedBy="project", fetch="EXTRA_LAZY")
     */
    protected $games;

    /**
     *  @ORM\OneToMany(targetEntity="GameTeam", mappedBy="project", fetch="EXTRA_LAZY")
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

    /**
     * Add phyTeams
     *
     * @param Zayso\ZaysoBundle\Entity\PhyTeam $phyTeams
     */
    public function addPhyTeams(\Zayso\ZaysoBundle\Entity\PhyTeam $phyTeams)
    {
        $this->phyTeams[] = $phyTeams;
    }

    /**
     * Get phyTeams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPhyTeams()
    {
        return $this->phyTeams;
    }

    /**
     * Add schTeams
     *
     * @param Zayso\ZaysoBundle\Entity\SchTeam $schTeams
     */
    public function addSchTeams(\Zayso\ZaysoBundle\Entity\SchTeam $schTeams)
    {
        $this->schTeams[] = $schTeams;
    }

    /**
     * Get schTeams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSchTeams()
    {
        return $this->schTeams;
    }

    /**
     * Add games
     *
     * @param Zayso\ZaysoBundle\Entity\Game $games
     */
    public function addGames(\Zayso\ZaysoBundle\Entity\Game $games)
    {
        $this->games[] = $games;
    }

    /**
     * Get games
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Add gameTeams
     *
     * @param Zayso\ZaysoBundle\Entity\GameTeam $gameTeams
     */
    public function addGameTeams(\Zayso\ZaysoBundle\Entity\GameTeam $gameTeams)
    {
        $this->gameTeams[] = $gameTeams;
    }

    /**
     * Get gameTeams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGameTeams()
    {
        return $this->gameTeams;
    }
}