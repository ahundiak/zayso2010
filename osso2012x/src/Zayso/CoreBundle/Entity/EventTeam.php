<?php
namespace Zayso\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Zayso\CoreBundle\Component\Debug;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_team",
     uniqueConstraints={
         @ORM\UniqueConstraint(name="game_team_type", columns={"event_id", "team_id", "type"})
   })
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class EventTeam extends BaseEntity
{   
    const TypeHome = 'Home';
    const TypeAway = 'Away';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="id")
     * @ORM\GeneratedValue
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="teams")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event = null;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team = null;
    
    /** 
     * home, away etc
     * @ORM\Column(type="string",name="type",length=20,nullable=false) 
     */
    protected $type = '';

    /** @ORM\Column(type="text", name="datax", nullable=true) */
    protected $datax = null;

    /* =========================================================
     * Custom code
     */
    
    /* =========================================================
     * Standard getter/setter
     */    
    public function getId() { return $this->id; }
    
    public function setGame($event) { $this->setEvent($event); }
    public function getGame()       { return $this->event;     }
    
    public function setEvent($event) { $this->onObjectPropertySet('event', $event); }
    public function getEvent()       { return $this->event;  }
    
    public function setTypeAsHome() { $this->setType(self::TypeHome); }
    public function setTypeAsAway() { $this->setType(self::TypeAway); }
    
    public function setType($type) { $this->onScalerPropertySet('type', $type); }
    public function getType()      { return $this->type;  }
    
    public function setTeam($team) { $this->onObjectPropertySet('team', $team); }
    public function getTeam()      { return $this->team;  }
}
?>
