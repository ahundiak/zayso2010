<?php

namespace Zayso\Osso2007Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeamPlayer
 *
 * @ORM\Table(name="phy_team_player")
 * @ORM\Entity
 */
class PhyTeamPlayer
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PhyTeam", inversedBy="players")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="phy_team_id")
     */
    private $phyTeam = null;

    /** @ORM\Column(name="fname", type="string", length=20, nullable=true) */
    private $fname;

    /** @ORM\Column(name="lname", type="string", length=20, nullable=true) */
    private $lname;

    /** @ORM\Column(name="aysoid", type="string", length=20, nullable=false) */
    private $aysoid;

    /** * @ORM\Column(name="jersey", type="integer", nullable=true) */
    private $jersey;

    public function setId       ($value) { $this->id      = $value; }
    public function setPhyTeam  ($value) { $this->phyTeam = $value; }
    public function setFirstName($value) { $this->fname   = $value; }
    public function setLastName ($value) { $this->lname   = $value; }
    public function setAysoid   ($value) { $this->aysoid  = $value; }
    public function setJersey   ($value) { $this->jersey  = $value; }

    public function getId       () { return $this->id; }
    public function getPhyTeam  () { return $this->phyTeam; }
    public function getFirstName() { return $this->fname; }
    public function getLastName () { return $this->lname; }
    public function getAysoid   () { return $this->aysoid; }
    public function getJersey   () { return $this->jersey; }

}