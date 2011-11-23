<?php
/* ------------------------------------------------
 * A Pool is a group of related games within a project
 * The teams could be doing a round robin sort of thing
 * Or it might be useful for quarter/semi/finals?
 */
namespace Zayso\ZaysoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="game_group")
 */
class GameGroup
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
    protected $project = null;

    /** @ORM\Column(type="string",name="group_key",length=40,nullable=false) */
    protected $groupKey = '';

    /** @ORM\Column(type="string",name="group_desc",length=40,nullable=false) */
    protected $groupDesc = '';

    /** @ORM\Column(type="string",name="group_type",length=40,nullable=false) */
    protected $groupType = '';

    /** @ORM\Column(type="string",name="calc_key",length=40,nullable=false) */
    protected $calcKey = '';

    /**
     *   Need to add pool relation to game
     *   ORM\OneToMany(targetEntity="Game", mappedBy="group", fetch="EXTRA_LAZY" )
     */
    protected $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    /* ===========================================================================
     * Generated code follows
     */

}