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
 * @ORM\Table(name="game_pool")
 */
class GamePool
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

    /** @ORM\Column(type="string",name="pool_key",length=40,nullable=false) */
    protected $poolKey = '';

    /** @ORM\Column(type="string",name="pool_desc",length=40,nullable=false) */
    protected $poolDesc = '';

    /** @ORM\Column(type="integer",name="calc_id",nullable=false) */
    protected $calcId = 0;

    /**
     *   Need to add pool relation to game
     *   ORM\OneToMany(targetEntity="Game", mappedBy="pool", fetch="EXTRA_LAZY" )
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