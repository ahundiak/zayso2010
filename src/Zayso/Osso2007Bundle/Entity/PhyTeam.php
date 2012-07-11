<?php

namespace Zayso\Osso2007Bundle\Entity;

use Zayso\Osso2007Bundle\Service\TeamManager as TeamManager;
use Zayso\Osso2007Bundle\Service\GameManager as GameManager;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Zayso\Osso2007Bundle\Entity\PhyTeam
 *
 * @ORM\Table(name="phy_team")
 * @ORM\Entity
 */
class PhyTeam
{
    /**
     *  @ORM\OneToMany(targetEntity="PhyTeamPerson", mappedBy="phyTeam", indexBy="volTypeId", fetch="EXTRA_LAZY" )
     */
    private $persons;

    /**
     *  @ORM\OneToMany(targetEntity="PhyTeamPlayer", mappedBy="phyTeam", indexBy="aysoid", fetch="EXTRA_LAZY" )
     */
    private $players;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->players = new ArrayCollection();
    }
    public function getPerson($type)
    {
        if (isset($this->persons[$type])) return $this->persons[$type]->getPerson();

        // Consider returning empty person object
        return null;
    }
    public function getProjectId()
    {
        if ($this->projectItem) return $this->projectItem->getProjectId();
        return 0;
    }
    public function getHeadCoach() { return $this->getPerson(TeamManager::TYPE_HEAD_COACH); }
    public function getAsstCoach() { return $this->getPerson(TeamManager::TYPE_ASST_COACH); }
    public function getManager  () { return $this->getPerson(TeamManager::TYPE_MANAGER); }

    public function getId() { return $this->phyTeamId; }

    public function getRegionKey()
    {
        return GameManager::getRegionKey($this->unitId);
    }
    public function getGenderKey()
    {
        return GameManager::getGenderKey($this->divisionId);
    }
    public function getAgeKey()
    {
        return GameManager::getAgeKey($this->divisionId);
    }

    public function getPlayers() { return $this->players; }
    public function getPlayer($aysoid)
    {
        if (isset($this->players[$aysoid])) return $this->players[$aysoid];
        return null;
    }
    public function getDesc()
    {
        $coach  = $this->getHeadCoach();
        if ($coach) $name = $coach->getLastName();
        else        $name = '';

        $desc = sprintf('%s-%s-%02d %s',$this->getRegionKey(),$this->getDivKey(),$this->divisionSeqNum,$name);
        return $desc;
    }
    /* ===================================================================================
     * Auto generated stuff
     */
    /**
     * @var integer $phyTeamId
     *
     * @ORM\Column(name="phy_team_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $phyTeamId;

    /**
     * @var integer $regYearId
     *
     * @ORM\Column(name="reg_year_id", type="integer", nullable=true)
     */
    private $regYearId;

    /**
     * @var integer $seasonTypeId
     *
     * @ORM\Column(name="season_type_id", type="integer", nullable=true)
     */
    private $seasonTypeId;

    /**
     * @var integer $unitId
     *
     *  ORM\Column(name="unit_id", type="integer", nullable=true)
     *
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="unit_id")
     */
    private $unit;

    /**
     * @var integer $divisionId
     *
     *  ORM\Column(name="division_id", type="integer", nullable=true)
     *
     * @ORM\ManyToOne(targetEntity="Division")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="division_id")
     */
    private $division;

    /**
     * @var integer $divisionSeqNum
     *
     * @ORM\Column(name="division_seq_num", type="integer", nullable=true)
     */
    private $divisionSeqNum;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=true)
     */
    private $name;

    /**
     * @var string $colors
     *
     * @ORM\Column(name="colors", type="string", length=20, nullable=true)
     */
    private $colors;

    /**
     * @var integer $eaysoId
     *
     * @ORM\Column(name="eayso_id", type="integer", nullable=true)
     */
    private $eaysoId;

    /**
     * @var string $eaysoDes
     *
     * @ORM\Column(name="eayso_des", type="string", length=20, nullable=true)
     */
    private $eaysoDes;


    /**
     * @ORM\OneToOne(targetEntity="ProjectItem")
     * @ORM\JoinColumn(name="phy_team_id", referencedColumnName="item_id")
     *
     * Still a bit of a hack, need Projectitem.type = 2
     */
    private $projectItem;

    /**
     * Set regYearId
     *
     * @param integer $regYearId
     */
    public function setRegYearId($regYearId)
    {
        $this->regYearId = $regYearId;
    }

    /**
     * Get regYearId
     *
     * @return integer 
     */
    public function getRegYearId()
    {
        return $this->regYearId;
    }

    /**
     * Set seasonTypeId
     *
     * @param integer $seasonTypeId
     */
    public function setSeasonTypeId($seasonTypeId)
    {
        $this->seasonTypeId = $seasonTypeId;
    }

    /**
     * Get seasonTypeId
     *
     * @return integer 
     */
    public function getSeasonTypeId()
    {
        return $this->seasonTypeId;
    }

    /**
     * Set unitId
     *
     * @param integer $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * Get unitId
     *
     * @return integer 
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * Set divisionId
     *
     * @param integer $divisionId
     */
    public function setDivisionId($divisionId)
    {
        $this->divisionId = $divisionId;
    }

    /**
     * Get divisionId
     *
     * @return integer 
     */
    public function getDivisionId()
    {
        return $this->divisionId;
    }

    /**
     * Set divisionSeqNum
     *
     * @param integer $divisionSeqNum
     */
    public function setDivisionSeqNum($divisionSeqNum)
    {
        $this->divisionSeqNum = $divisionSeqNum;
    }

    /**
     * Get divisionSeqNum
     *
     * @return integer 
     */
    public function getDivisionSeqNum()
    {
        return $this->divisionSeqNum;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set colors
     *
     * @param string $colors
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
    }

    /**
     * Get colors
     *
     * @return string 
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set eaysoId
     *
     * @param integer $eaysoId
     */
    public function setEaysoId($eaysoId)
    {
        $this->eaysoId = $eaysoId;
    }

    /**
     * Get eaysoId
     *
     * @return integer 
     */
    public function getEaysoId()
    {
        return $this->eaysoId;
    }

    /**
     * Set eaysoDes
     *
     * @param string $eaysoDes
     */
    public function setEaysoDes($eaysoDes)
    {
        $this->eaysoDes = $eaysoDes;
    }

    /**
     * Get eaysoDes
     *
     * @return string 
     */
    public function getEaysoDes()
    {
        return $this->eaysoDes;
    }

    /**
     * Get phyTeamId
     *
     * @return integer 
     */
    public function getPhyTeamId()
    {
        return $this->phyTeamId;
    }
}