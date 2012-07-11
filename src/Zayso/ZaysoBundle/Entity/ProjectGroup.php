<?php

namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project_group")
 */
class ProjectGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\Column(type="string",length="32",name="keyx") */
    protected $key = '';

    /** @ORM\Column(type="string",length="80",name="descx") */
    protected $description = '';

    /** @ORM\Column(type="string",length="32",name="status") */
    protected $status = '';

    public function __construct()
    {
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

    public function setKey($key) { $this->key = $key; }
    public function getKey() { return $this->key; }

     /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

   /**
     * Get description
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